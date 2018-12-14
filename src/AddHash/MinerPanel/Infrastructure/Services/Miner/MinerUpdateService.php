<?php

namespace App\AddHash\MinerPanel\Infrastructure\Services\Miner;

use App\AddHash\MinerPanel\Domain\Miner\MinerRepositoryInterface;
use App\AddHash\MinerPanel\Domain\Miner\Command\MinerUpdateCommandInterface;
use App\AddHash\MinerPanel\Infrastructure\Transformers\Miner\MinerTransform;
use App\AddHash\MinerPanel\Domain\Miner\Services\MinerUpdateServiceInterface;
use App\AddHash\MinerPanel\Application\Command\IpAddress\IpAddressCheckCommand;
use App\AddHash\MinerPanel\Domain\Miner\MinerType\MinerTypeRepositoryInterface;
use App\AddHash\MinerPanel\Domain\Miner\Exceptions\MinerUpdateInvalidTypeException;
use App\AddHash\MinerPanel\Domain\Miner\Exceptions\MinerUpdateInvalidDataException;
use App\AddHash\MinerPanel\Domain\Miner\Exceptions\MinerUpdateInvalidMinerException;
use App\AddHash\MinerPanel\Domain\IpAddress\Services\IpAddressCheckServiceInterface;
use App\AddHash\MinerPanel\Domain\Miner\MinerInfo\MinerInfoPoolsGetHandlerInterface;
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

    private $ipAddressCheckService;

    private $summaryGetHandler;

    private $poolsGetHandler;

    private $calcIncomeHandler;

    public function __construct(
        UserAuthenticationGetServiceInterface $authenticationAdapter,
        MinerRepositoryInterface $minerRepository,
        MinerAlgorithmRepositoryInterface $minerAlgorithmRepository,
        MinerTypeRepositoryInterface $minerTypeRepository,
        IpAddressCheckServiceInterface $ipAddressCheckService,
        MinerInfoSummaryGetHandlerInterface $summaryGetHandler,
        MinerInfoPoolsGetHandlerInterface $poolsGetHandler,
        MinerCalcIncomeHandlerInterface $calcIncomeHandler
    )
    {
        $this->authenticationAdapter = $authenticationAdapter;
        $this->minerRepository = $minerRepository;
        $this->minerAlgorithmRepository = $minerAlgorithmRepository;
        $this->minerTypeRepository = $minerTypeRepository;
        $this->ipAddressCheckService = $ipAddressCheckService;
        $this->summaryGetHandler = $summaryGetHandler;
        $this->poolsGetHandler = $poolsGetHandler;
        $this->calcIncomeHandler = $calcIncomeHandler;
    }

    /**
     * @param MinerUpdateCommandInterface $command
     * @return array
     * @throws MinerUpdateInvalidAlgorithmException
     * @throws MinerUpdateInvalidDataException
     * @throws MinerUpdateInvalidMinerException
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

        $errors = [];

        $ip = $command->getIp();
        $port = $command->getPort();
        $ipAddressCheckCommand = new IpAddressCheckCommand($ip, $port);

        try {
            $this->ipAddressCheckService->execute($ipAddressCheckCommand);
        } catch (IpAddressCheckIpAddressUnavailableException $e) {
            $errors = $e->getMessage();
        }

        $title = $command->getTitle();
        $minerData = $this->minerRepository->getMinerByTitleAndUser($title, $user);

        if (null !== $minerData && $minerData->getId() != $miner->getId()) {
            $errors['title'] = ['Title already taken'];
        }

        if (false === empty($errors)) {
            throw new MinerUpdateInvalidDataException($errors);
        }

        $updateCache = ($miner->getIp() != $ip || $miner->getPort() != $port);

        $miner->setTitle($title);
        $miner->setIp($ip);
        $miner->setPort($port);
        $miner->setType($minerType);
        $miner->setAlgorithm($minerAlgorithm);

        $summary = $this->summaryGetHandler->handler($miner, $updateCache);

        $hashRate = (false === empty($summary)) ? $summary['hashRateAverage']: 0;

        $miner->setHashRate($hashRate);

        $this->minerRepository->save($miner);

        $pools['pools'] = $this->poolsGetHandler->handler($miner, $updateCache);

        $coins['coins'] = $this->calcIncomeHandler->handler($miner);

        return (new MinerTransform())->transform($miner) + $summary + $pools + $coins;
    }
}