<?php

namespace App\AddHash\MinerPanel\Infrastructure\Services\Miner;

use App\AddHash\MinerPanel\Domain\User\User;
use App\AddHash\MinerPanel\Domain\Miner\Miner;
use App\AddHash\MinerPanel\Domain\Miner\MinerRepositoryInterface;
use App\AddHash\MinerPanel\Infrastructure\Transformers\Miner\MinerTransform;
use App\AddHash\MinerPanel\Domain\Miner\Command\MinerCreateCommandInterface;
use App\AddHash\MinerPanel\Domain\Miner\Services\MinerCreateServiceInterface;
use App\AddHash\MinerPanel\Application\Command\IpAddress\IpAddressCheckCommand;
use App\AddHash\MinerPanel\Domain\Miner\MinerType\MinerTypeRepositoryInterface;
use App\AddHash\MinerPanel\Domain\Miner\Exceptions\MinerCreateInvalidTypeException;
use App\AddHash\MinerPanel\Domain\Miner\Exceptions\MinerCreateInvalidDataException;
use App\AddHash\MinerPanel\Domain\IpAddress\Services\IpAddressCheckServiceInterface;
use App\AddHash\MinerPanel\Domain\Miner\MinerInfo\MinerInfoPoolsGetHandlerInterface;
use App\AddHash\MinerPanel\Domain\Miner\MinerInfo\MinerInfoSummaryGetHandlerInterface;
use App\AddHash\MinerPanel\Domain\User\Services\UserAuthenticationGetServiceInterface;
use App\AddHash\MinerPanel\Domain\Miner\Exceptions\MinerCreateMaxQtyFreeMinerException;
use App\AddHash\MinerPanel\Domain\Miner\Exceptions\MinerCreateInvalidAlgorithmException;
use App\AddHash\MinerPanel\Domain\Miner\MinerAlgorithm\MinerAlgorithmRepositoryInterface;
use App\AddHash\MinerPanel\Domain\IpAddress\Exceptions\IpAddressCheckIpAddressUnavailableException;

final class MinerCreateService implements MinerCreateServiceInterface
{
    private const MAX_QTY_FREE_MINER = 50;


    private $authenticationAdapter;

    private $minerRepository;

    private $minerAlgorithmRepository;

    private $minerTypeRepository;

    private $ipAddressCheckService;

    private $summaryGetHandler;

    private $poolsGetHandler;

    public function __construct(
        UserAuthenticationGetServiceInterface $authenticationAdapter,
        MinerRepositoryInterface $minerRepository,
        MinerAlgorithmRepositoryInterface $minerAlgorithmRepository,
        MinerTypeRepositoryInterface $minerTypeRepository,
        IpAddressCheckServiceInterface $ipAddressCheckService,
        MinerInfoSummaryGetHandlerInterface $summaryGetHandler,
        MinerInfoPoolsGetHandlerInterface $poolsGetHandler
    )
    {
        $this->authenticationAdapter = $authenticationAdapter;
        $this->minerRepository = $minerRepository;
        $this->minerAlgorithmRepository = $minerAlgorithmRepository;
        $this->minerTypeRepository = $minerTypeRepository;
        $this->ipAddressCheckService = $ipAddressCheckService;
        $this->summaryGetHandler = $summaryGetHandler;
        $this->poolsGetHandler = $poolsGetHandler;
    }

    /**
     * @param MinerCreateCommandInterface $command
     * @return array
     * @throws MinerCreateInvalidAlgorithmException
     * @throws MinerCreateInvalidDataException
     * @throws MinerCreateInvalidTypeException
     * @throws MinerCreateMaxQtyFreeMinerException
     */
    public function execute(MinerCreateCommandInterface $command): array
    {
        $user = $this->authenticationAdapter->execute();

        if (false === $this->checkAddFreeMiner($user)) {
            throw new MinerCreateMaxQtyFreeMinerException('The limit of adding free miners is exceeded');
        }

        $minerType = $this->minerTypeRepository->get($command->getTypeId());

        if (null === $minerType) {
            throw new MinerCreateInvalidTypeException('Invalid type id');
        }

        $minerAlgorithm = $this->minerAlgorithmRepository->get($command->getAlgorithmId());

        if (null === $minerAlgorithm) {
            throw new MinerCreateInvalidAlgorithmException('Invalid algorithm id');
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
        $miner = $this->minerRepository->getMinerByTitleAndUser($title, $user);

        if (null !== $miner) {
            $errors['title'] = ['Title already taken'];
        }

        if (false === empty($errors)) {
            throw new MinerCreateInvalidDataException($errors);
        }

        $miner = new Miner(
            $title,
            $ip,
            $port,
            $minerType,
            $minerAlgorithm,
            $user
        );

        $this->minerRepository->save($miner);

        $summary = $this->summaryGetHandler->handler($miner);

        $pools = $this->poolsGetHandler->handler($miner);

        return (new MinerTransform())->transform($miner) + $summary + $pools;
    }

    private function checkAddFreeMiner(User $user): bool
    {
        $count = $this->minerRepository->getCountByUser($user);

        return $count < self::MAX_QTY_FREE_MINER;
    }
}