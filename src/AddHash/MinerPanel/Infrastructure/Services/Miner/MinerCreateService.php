<?php

namespace App\AddHash\MinerPanel\Infrastructure\Services\Miner;

use App\AddHash\MinerPanel\Domain\User\Model\User;
use App\AddHash\MinerPanel\Domain\Miner\Model\Miner;
use App\AddHash\MinerPanel\Domain\Miner\Summary\SummaryGetHandlerInterface;
use App\AddHash\MinerPanel\Domain\Miner\Repository\MinerRepositoryInterface;
use App\AddHash\MinerPanel\Infrastructure\Transformers\Miner\MinerTransform;
use App\AddHash\MinerPanel\Domain\Miner\Command\MinerCreateCommandInterface;
use App\AddHash\MinerPanel\Domain\Miner\Services\MinerCreateServiceInterface;
use App\AddHash\MinerPanel\Application\Command\IpAddress\IpAddressCheckCommand;
use App\AddHash\MinerPanel\Domain\Miner\Exceptions\MinerCreateInvalidTypeException;
use App\AddHash\MinerPanel\Domain\Miner\Exceptions\MinerCreateInvalidDataException;
use App\AddHash\MinerPanel\Domain\IpAddress\Services\IpAddressCheckServiceInterface;
use App\AddHash\MinerPanel\Domain\User\Services\UserAuthenticationGetServiceInterface;
use App\AddHash\MinerPanel\Domain\Miner\Exceptions\MinerCreateMaxQtyFreeMinerException;
use App\AddHash\MinerPanel\Domain\Miner\Exceptions\MinerCreateInvalidAlgorithmException;
use App\AddHash\MinerPanel\Domain\Miner\MinerType\Repository\MinerTypeRepositoryInterface;
use App\AddHash\MinerPanel\Domain\IpAddress\Exceptions\IpAddressCheckIpAddressUnavailableException;
use App\AddHash\MinerPanel\Domain\Miner\MinerAlgorithm\Repository\MinerAlgorithmRepositoryInterface;

final class MinerCreateService implements MinerCreateServiceInterface
{
    private const MAX_QTY_FREE_MINER = 50;


    private $authenticationAdapter;

    private $minerRepository;

    private $minerAlgorithmRepository;

    private $minerTypeRepository;

    private $ipAddressCheckService;

    private $summaryGetHandler;

    public function __construct(
        UserAuthenticationGetServiceInterface $authenticationAdapter,
        MinerRepositoryInterface $minerRepository,
        MinerAlgorithmRepositoryInterface $minerAlgorithmRepository,
        MinerTypeRepositoryInterface $minerTypeRepository,
        IpAddressCheckServiceInterface $ipAddressCheckService,
        SummaryGetHandlerInterface $summaryGetHandler
    )
    {
        $this->authenticationAdapter = $authenticationAdapter;
        $this->minerRepository = $minerRepository;
        $this->minerAlgorithmRepository = $minerAlgorithmRepository;
        $this->minerTypeRepository = $minerTypeRepository;
        $this->ipAddressCheckService = $ipAddressCheckService;
        $this->summaryGetHandler = $summaryGetHandler;
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
        /** @var User $user */
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

        return (new MinerTransform())->transform($miner) + $summary;
    }

    private function checkAddFreeMiner(User $user): bool
    {
        $count = $this->minerRepository->getCountByUser($user);

        return $count < self::MAX_QTY_FREE_MINER;
    }
}