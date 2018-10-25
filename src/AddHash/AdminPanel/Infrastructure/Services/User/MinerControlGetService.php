<?php

namespace App\AddHash\AdminPanel\Infrastructure\Services\User;

use App\AddHash\AdminPanel\Domain\Miners\MinerStock;
use App\AddHash\AdminPanel\Domain\User\Order\UserOrderMiner;
use App\AddHash\AdminPanel\Domain\User\Miner\UserMinerRepositoryInterface;
use App\AddHash\AdminPanel\Domain\User\Services\MinerControlGetServiceInterface;
use App\AddHash\AdminPanel\Domain\User\Services\UserGetAuthenticationServiceInterface;

final class MinerControlGetService implements MinerControlGetServiceInterface
{
    private $authenticationService;

    private $userMinerRepository;

    public function __construct(
        UserGetAuthenticationServiceInterface $authenticationService,
	    UserMinerRepositoryInterface $userMinerRepository)
    {
        $this->authenticationService = $authenticationService;
        $this->userMinerRepository = $userMinerRepository;
    }

    /**
     * @return array
     */
    public function execute(): array
	{
        $user = $this->authenticationService->execute();

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