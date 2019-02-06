<?php

namespace App\AddHash\MinerPanel\Infrastructure\Services\Dashboard;

use App\AddHash\MinerPanel\Domain\Miner\Miner;
use App\AddHash\MinerPanel\Domain\Miner\MinerRepositoryInterface;
use App\AddHash\MinerPanel\Domain\Dashboard\Services\DashboardListServiceInterface;
use App\AddHash\MinerPanel\Domain\User\Services\UserAuthenticationGetServiceInterface;

final class DashboardListService implements DashboardListServiceInterface
{
    private const TOTAL_DEFAULT_VALUE = 0;

    private const ACTIVE_DEFAULT_VALUE = 0;

    private const HASH_RATE_DEFAULT_VALUE = 0;


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

    public function execute(): array
    {
        $user = $this->authenticationAdapter->execute();

        $data = [
            'total'    => self::TOTAL_DEFAULT_VALUE,
            'active'   => self::ACTIVE_DEFAULT_VALUE,
            'hashRate' => self::HASH_RATE_DEFAULT_VALUE,
            'type'     => [],
        ];

        $miners = $this->minerRepository->getCountMinersByUserGroupByType($user);

        foreach ($miners as $m) {
            /** @var  $miner Miner */
            $miner = $m['miner'];
            $typeId = $miner->getType()->getId();

            $data['total'] += $m['count'];
            $data['type'][$typeId] = [
                'total'    => $m['count'],
                'active'   => self::ACTIVE_DEFAULT_VALUE,
                'hashRate' => self::HASH_RATE_DEFAULT_VALUE,
                'typeId'   => $typeId
            ];
        }

        $minersActive = $this->minerRepository->getCountAndAvgHashRateActiveMinersByUserGroupByType($user);

        foreach ($minersActive as $ma) {
            /** @var  $miner Miner */
            $miner = $ma['miner'];
            $typeId = $miner->getType()->getId();

            $data['active'] += $ma['count'];
            $data['hashRate'] += $ma['hashRateAvg'];

            $data['type'][$typeId]['active'] = $ma['count'];
            $data['type'][$typeId]['hashRate'] = $ma['hashRateAvg'];
        }

        $data['hashRate'] = $data['hashRate'] / $data['active'];
        $data['incomeBtc'] = $this->calcIncomeBtc();

        return $data;
    }

    private function calcIncomeBtc(): array
    {
        #ToDo Test data
        return [
            'day'   => 10,
            'week'  => 100,
            'month' => 250,
        ];
    }
}