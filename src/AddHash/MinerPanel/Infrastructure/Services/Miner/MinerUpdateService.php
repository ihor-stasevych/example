<?php

namespace App\AddHash\MinerPanel\Infrastructure\Services\Miner;

use App\AddHash\MinerPanel\Domain\Rig\RigRepositoryInterface;
use App\AddHash\MinerPanel\Domain\Miner\MinerRepositoryInterface;
use App\AddHash\MinerPanel\Domain\Miner\MinerCredential\MinerCredential;
use App\AddHash\MinerPanel\Domain\Miner\Command\MinerUpdateCommandInterface;
use App\AddHash\MinerPanel\Infrastructure\Transformers\Miner\MinerTransform;
use App\AddHash\MinerPanel\Domain\Miner\Services\MinerUpdateServiceInterface;
use App\AddHash\MinerPanel\Application\Command\IpAddress\IpAddressCheckCommand;
use App\AddHash\MinerPanel\Domain\Miner\MinerType\MinerTypeRepositoryInterface;
use App\AddHash\MinerPanel\Domain\Miner\Exceptions\MinerUpdateInvalidRigException;
use App\AddHash\MinerPanel\Domain\Miner\MinerInfo\MinerInfoPoolGetHandlerInterface;
use App\AddHash\MinerPanel\Domain\Miner\Exceptions\MinerUpdateInvalidTypeException;
use App\AddHash\MinerPanel\Domain\Miner\Exceptions\MinerUpdateInvalidDataException;
use App\AddHash\MinerPanel\Domain\Miner\Exceptions\MinerUpdateInvalidMinerException;
use App\AddHash\MinerPanel\Domain\IpAddress\Services\IpAddressCheckServiceInterface;
use App\AddHash\MinerPanel\Domain\User\Services\UserAuthenticationGetServiceInterface;
use App\AddHash\MinerPanel\Domain\Miner\MinerInfo\MinerInfoSummaryGetHandlerInterface;
use App\AddHash\MinerPanel\Domain\Miner\Exceptions\MinerUpdateInvalidAlgorithmException;
use App\AddHash\MinerPanel\Domain\Miner\MinerCalcIncome\MinerCalcIncomeHandlerInterface;
use App\AddHash\MinerPanel\Domain\Miner\MinerAlgorithm\MinerAlgorithmRepositoryInterface;
use App\AddHash\MinerPanel\Domain\IpAddress\Exceptions\IpAddressCheckIpAddressUnavailableException;

final class MinerUpdateService implements MinerUpdateServiceInterface
{
    private $authenticationAdapter;

    private $minerRepository;

    private $minerAlgorithmRepository;

    private $minerTypeRepository;

    private $rigRepository;

    private $ipAddressCheckService;

    private $summaryGetHandler;

    private $poolGetHandler;

    private $calcIncomeHandler;

    public function __construct(
        UserAuthenticationGetServiceInterface $authenticationAdapter,
        MinerRepositoryInterface $minerRepository,
        MinerAlgorithmRepositoryInterface $minerAlgorithmRepository,
        MinerTypeRepositoryInterface $minerTypeRepository,
        RigRepositoryInterface $rigRepository,
        IpAddressCheckServiceInterface $ipAddressCheckService,
        MinerInfoSummaryGetHandlerInterface $summaryGetHandler,
        MinerInfoPoolGetHandlerInterface $poolGetHandler,
        MinerCalcIncomeHandlerInterface $calcIncomeHandler
    )
    {
        $this->authenticationAdapter = $authenticationAdapter;
        $this->minerRepository = $minerRepository;
        $this->minerAlgorithmRepository = $minerAlgorithmRepository;
        $this->minerTypeRepository = $minerTypeRepository;
        $this->rigRepository = $rigRepository;
        $this->ipAddressCheckService = $ipAddressCheckService;
        $this->summaryGetHandler = $summaryGetHandler;
        $this->poolGetHandler = $poolGetHandler;
        $this->calcIncomeHandler = $calcIncomeHandler;
    }

    /**
     * @param MinerUpdateCommandInterface $command
     * @return array
     * @throws MinerUpdateInvalidAlgorithmException
     * @throws MinerUpdateInvalidDataException
     * @throws MinerUpdateInvalidMinerException
     * @throws MinerUpdateInvalidRigException
     * @throws MinerUpdateInvalidTypeException
     */
    public function execute(MinerUpdateCommandInterface $command): array
    {
        $user = $this->authenticationAdapter->execute();

        $miner = $this->minerRepository->getMinerByIdAndUser($command->getId(), $user);

        if (null === $miner) {
            throw new MinerUpdateInvalidMinerException('Invalid miner');
        }

        $minerType = $this->minerTypeRepository->get($command->getTypeId());

        if (null === $minerType) {
            throw new MinerUpdateInvalidTypeException('Invalid type id');
        }

        $minerAlgorithm = $this->minerAlgorithmRepository->get($command->getAlgorithmId());

        if (null === $minerAlgorithm) {
            throw new MinerUpdateInvalidAlgorithmException('Invalid algorithm id');
        }

        $rig = null;
        $rigId = $command->getRigId();

        if (null !== $rigId) {
            $rig = $this->rigRepository->existRigByIdAndUser($rigId, $user);

            if (null === $rig) {
                throw new MinerUpdateInvalidRigException('Invalid rig id');
            }
        }

        $errors = [];

        $ip = $command->getIp();
        $port = $command->getPort();
        $portSsh = $command->getPortSsh();

        $portError = $this->checkValidIdAndPort($ip, $port);

        if (false === empty($portError)) {
            $errors['port'] = $portError;
        }

        if (null !== $portSsh) {
            $portSshError = $this->checkValidIdAndPort($ip, $portSsh);

            if (false === empty($portSshError)) {
                $errors['portSsh'] = $portSshError;
            }
        }

        $title = $command->getTitle();
        $minerData = $this->minerRepository->getMinerByTitleAndUser($title, $user);

        if (null !== $minerData && $minerData->getId() != $miner->getId()) {
            $errors['title'] = ['Title already taken'];
        }

        if (false === empty($errors)) {
            throw new MinerUpdateInvalidDataException($errors);
        }

        $minerCredential = $miner->getCredential();

        $updateCache = ($minerCredential->getIp() != $ip || $minerCredential->getPort() != ($port ?? MinerCredential::DEFAULT_PORT));

        $minerCredential->setIp($ip);
        $minerCredential->setPort($port);
        $minerCredential->setPortSsh(null);
        $minerCredential->setLoginSsh(null);
        $minerCredential->setPasswordSsh(null);

        if (null !== $portSsh) {
            $minerCredential->setPortSsh($portSsh);
            $minerCredential->setLoginSsh($command->getLoginSsh());
            $minerCredential->setPasswordSsh($command->getPasswordSsh());
        }

        $miner->setTitle($title);
        $miner->setType($minerType);
        $miner->setAlgorithm($minerAlgorithm);
        $miner->setCredential($minerCredential);

        $summary = $this->summaryGetHandler->handler($minerCredential, $updateCache);

        $hashRate = (false === empty($summary)) ? $summary['hashRateAverage']: 0;

        $miner->setHashRate($hashRate);

        $oldRig = $miner->infoRigs()->first();

        if (false !== $oldRig) {
            $miner->removeRig($oldRig);
        }

        if (null !== $rig) {
            $miner->setRig($rig);
        }

        $this->minerRepository->save($miner);

        if (null !== $rig) {
            #ToDo change pools miners
        }

        $minerInfo = [
            'summary' => $summary,
            'pools'   => $this->poolGetHandler->handler($minerCredential),
            'coins'   => $this->calcIncomeHandler->handler($miner),
        ];

        return (new MinerTransform())->transform($miner) + $minerInfo;
    }

    private function checkValidIdAndPort(string $ip, int $port): string
    {
        $ipAddressCheckCommand = new IpAddressCheckCommand($ip, $port);

        $errors = '';

        try {
            $this->ipAddressCheckService->execute($ipAddressCheckCommand);
        } catch (IpAddressCheckIpAddressUnavailableException $e) {
            $errors = $e->getMessage();
        }

        return $errors;
    }
}