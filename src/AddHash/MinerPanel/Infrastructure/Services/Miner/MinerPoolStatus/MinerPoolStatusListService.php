<?php

namespace App\AddHash\MinerPanel\Infrastructure\Services\Miner\MinerPoolStatus;

use App\AddHash\MinerPanel\Domain\Miner\MinerRepositoryInterface;
use App\AddHash\MinerPanel\Domain\User\Services\UserAuthenticationGetServiceInterface;
use App\AddHash\MinerPanel\Infrastructure\Transformers\MinerPoolStatus\MinerPoolStatusTransform;
use App\AddHash\MinerPanel\Domain\Miner\MinerPoolStatus\Command\MinerPoolStatusListCommandInterface;
use App\AddHash\MinerPanel\Domain\Miner\MinerPoolStatus\Services\MinerPoolStatusListServiceInterface;
use App\AddHash\MinerPanel\Domain\Miner\MinerPoolStatus\Exceptions\MinerPoolStatusListInvalidMinersIdException;

final class MinerPoolStatusListService implements MinerPoolStatusListServiceInterface
{
    private $authenticationAdapter;

    private $minerRepository;

    public function __construct(
        UserAuthenticationGetServiceInterface $authenticationAdapter,
        MinerRepositoryInterface $minerRepository
    )
    {
        $this->authenticationAdapter = $authenticationAdapter;
        $this->minerRepository = $minerRepository;
    }

    /**
     * @param MinerPoolStatusListCommandInterface $command
     * @return array
     * @throws MinerPoolStatusListInvalidMinersIdException
     */
    public function execute(MinerPoolStatusListCommandInterface $command): array
    {
        $minersId = $command->getMinersId();
        $user = $this->authenticationAdapter->execute();

        $miners = $this->minerRepository->getMinersStatusByIdsAndUser($minersId, $user);

        if (count($miners) != count($minersId)) {
            throw new MinerPoolStatusListInvalidMinersIdException('Invalid miners id');
        }

        $transform = new MinerPoolStatusTransform();
        $data = [];

        foreach ($miners as $miner) {
            $data[] = $transform->transform($miner);
        }

        return $data;
    }
}