<?php

namespace App\AddHash\AdminPanel\Infrastructure\Services\User;

use App\AddHash\AdminPanel\Domain\User\Miner\UserMinerRepositoryInterface;
use App\AddHash\AdminPanel\Domain\User\User;
use App\AddHash\AdminPanel\Domain\Miners\MinerStock;
use App\AddHash\AdminPanel\Domain\User\Order\UserOrderMiner;
use App\AddHash\AdminPanel\Domain\User\Services\MinerControlGetServiceInterface;
use App\AddHash\AdminPanel\Domain\User\Exceptions\MinerControlNoMainerException;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

class MinerControlGetService implements MinerControlGetServiceInterface
{
    private $tokenStorage;
    private $userMinerRepository;

    public function __construct(
    	TokenStorageInterface $tokenStorage,
	    UserMinerRepositoryInterface $userMinerRepository)
    {
        $this->tokenStorage = $tokenStorage;
        $this->userMinerRepository = $userMinerRepository;
    }

    /**
     * @return array
     * @throws MinerControlNoMainerException
     */
    public function execute(): array
	{
	    /** @var User $user */
        $user = $this->tokenStorage->getToken()->getUser();

        if (!count($user->getOrderMiner())) {
            return [];
        }

        $data = [];

        /** @var UserOrderMiner $orderMiners */
		foreach ($user->getOrderMiner() as $orderMiners) {
			/** @var MinerStock $minerStock */
			foreach ($orderMiners->getMiners() as $minerStock) {
                $data[] = $this->userMinerRepository->getSummary($minerStock);
            }
        }

        return $data;
	}
}