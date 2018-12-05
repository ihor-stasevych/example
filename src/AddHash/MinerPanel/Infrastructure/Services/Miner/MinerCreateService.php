<?php

namespace App\AddHash\MinerPanel\Infrastructure\Services\Miner;

use App\AddHash\MinerPanel\Domain\Miner\Model\Miner;
use App\AddHash\MinerPanel\Domain\Miner\Repository\MinerRepositoryInterface;
use App\AddHash\MinerPanel\Infrastructure\Transformers\Miner\MinerTransform;
use App\AddHash\MinerPanel\Domain\Miner\Command\MinerCreateCommandInterface;
use App\AddHash\MinerPanel\Domain\Miner\Services\MinerCreateServiceInterface;
use App\AddHash\MinerPanel\Application\Command\IpAddress\IpAddressCheckCommand;
use App\AddHash\MinerPanel\Domain\Miner\Repository\MinerTypeRepositoryInterface;
use App\AddHash\MinerPanel\Domain\Miner\Exceptions\MinerCreateInvalidTypeException;
use App\AddHash\MinerPanel\Domain\Miner\Exceptions\MinerCreateInvalidDataException;
use App\AddHash\MinerPanel\Domain\IpAddress\Services\IpAddressCheckServiceInterface;
use App\AddHash\MinerPanel\Domain\Miner\Repository\MinerAlgorithmRepositoryInterface;
use App\AddHash\MinerPanel\Domain\User\Services\UserAuthenticationGetServiceInterface;
use App\AddHash\MinerPanel\Domain\Miner\Exceptions\MinerCreateInvalidAlgorithmException;
use App\AddHash\MinerPanel\Domain\IpAddress\Exceptions\IpAddressCheckIpAddressUnavailableException;

final class MinerCreateService implements MinerCreateServiceInterface
{
    private $authenticationAdapter;

    private $minerRepository;

    private $minerAlgorithmRepository;

    private $minerTypeRepository;

    private $ipAddressCheckService;

    public function __construct(
        UserAuthenticationGetServiceInterface $authenticationAdapter,
        MinerRepositoryInterface $minerRepository,
        MinerAlgorithmRepositoryInterface $minerAlgorithmRepository,
        MinerTypeRepositoryInterface $minerTypeRepository,
        IpAddressCheckServiceInterface $ipAddressCheckService
    )
    {
        $this->authenticationAdapter = $authenticationAdapter;
        $this->minerRepository = $minerRepository;
        $this->minerAlgorithmRepository = $minerAlgorithmRepository;
        $this->minerTypeRepository = $minerTypeRepository;
        $this->ipAddressCheckService = $ipAddressCheckService;
    }

    /**
     * @param MinerCreateCommandInterface $command
     * @return array
     * @throws MinerCreateInvalidAlgorithmException
     * @throws MinerCreateInvalidDataException
     * @throws MinerCreateInvalidTypeException
     */
    public function execute(MinerCreateCommandInterface $command): array
    {
        $minerType = $this->minerTypeRepository->get($command->getTypeId());

        if (null === $minerType) {
            throw new MinerCreateInvalidTypeException('Invalid type id');
        }

        $minerAlgorithm = $this->minerAlgorithmRepository->get($command->getAlgorithmId());

        if (null === $minerAlgorithm) {
            throw new MinerCreateInvalidAlgorithmException('Invalid algorithm id');
        }

        $user = $this->authenticationAdapter->execute();

        $errors = [];

        $ip = $command->getIp();
        $ipAddressCheckCommand = new IpAddressCheckCommand($ip);

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
            $command->getPort(),
            $minerType,
            $minerAlgorithm,
            $user
        );

        $this->minerRepository->save($miner);

        return (new MinerTransform())->transform($miner);
    }
}