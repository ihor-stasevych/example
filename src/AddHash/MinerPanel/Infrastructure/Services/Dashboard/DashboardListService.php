<?php

namespace App\AddHash\MinerPanel\Infrastructure\Services\Dashboard;

use App\AddHash\MinerPanel\Domain\Miner\Miner;
use App\AddHash\MinerPanel\Domain\Miner\MinerRepositoryInterface;
use App\AddHash\MinerPanel\Domain\Dashboard\Services\DashboardListServiceInterface;
use App\AddHash\MinerPanel\Domain\Currency\Services\CryptoCurrencyGetServiceInterface;
use App\AddHash\MinerPanel\Domain\User\Services\UserAuthenticationGetServiceInterface;
use App\AddHash\MinerPanel\Infrastructure\Miner\MinerCalcIncome\MinerCalcIncomeStrategy;
use App\AddHash\MinerPanel\Domain\Miner\MinerAlgorithm\MinerCoin\MinerCoinRepositoryInterface;
use App\AddHash\MinerPanel\Infrastructure\Miner\MinerCalcIncome\Algorithms\MinerCalcIncomeAlgorithmSHA256;

final class DashboardListService implements DashboardListServiceInterface
{
    private const TOTAL_DEFAULT_VALUE = 0;

    private const ACTIVE_DEFAULT_VALUE = 0;

    private const HASH_RATE_DEFAULT_VALUE = 0;

    private const DAYS_INCOME = [
        'day'   => 1,
        'week'  => 7,
        'month' => 30,
    ];

    private const ONE_DAY_IN_SECONDS = 86400;

    private const DEFAULT_COIN = 'BTC';


    private $authenticationAdapter;

    private $minerRepository;

    private $coinRepository;

    private $cryptoCurrencyGetService;

    public function __construct(
        UserAuthenticationGetServiceInterface $authenticationAdapter,
        MinerRepositoryInterface $minerRepository,
        MinerCoinRepositoryInterface $coinRepository,
        CryptoCurrencyGetServiceInterface $cryptoCurrencyGetService
    )
    {
        $this->authenticationAdapter = $authenticationAdapter;
        $this->minerRepository = $minerRepository;
        $this->coinRepository = $coinRepository;
        $this->cryptoCurrencyGetService = $cryptoCurrencyGetService;
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
                'title'    => $miner->getType()->getValue(),
                'total'    => $m['count'],
                'active'   => self::ACTIVE_DEFAULT_VALUE,
                'hashRate' => self::HASH_RATE_DEFAULT_VALUE,
            ];
        }

        $minersActive = $this->minerRepository->getCountAndAvgHashRateActiveMinersByUserGroupByType($user);
        $hashRateBtc = 0;
        $countBtc = 0;

        foreach ($minersActive as $ma) {
            /** @var  $miner Miner */
            $miner = $ma['miner'];
            $typeId = $miner->getType()->getId();

            if ($miner->getType()->getValue() == 'ASIC') {
                $hashRateBtc = $ma['hashRateAvg'];
                $countBtc = $ma['count'];
            }

            $data['active'] += $ma['count'];
            $data['hashRate'] += $ma['hashRateAvg'];

            $data['type'][$typeId]['active'] = $ma['count'];
            $data['type'][$typeId]['hashRate'] = $ma['hashRateAvg'];
        }

        $data['hashRate'] = $data['hashRate'] != 0 ? $data['hashRate'] / count($minersActive) : 0;

        $data['currenciesIncome'] = $this->calcIncome($hashRateBtc, $countBtc);

        return $data;
    }

    private function calcIncome(string $hashRate, int $count): array
    {
        try {
            $currencies = $this->cryptoCurrencyGetService->execute();
        } catch (\Exception $e) {
            $currencies = [];
        }

        $coinBtc = $this->coinRepository->getByTag(self::DEFAULT_COIN);
        $algorithm = new MinerCalcIncomeAlgorithmSHA256();
        $minerCalc = new MinerCalcIncomeStrategy($algorithm);

        $data = [];

        foreach (self::DAYS_INCOME as $name => $number) {
            $crypto = $minerCalc->execute($hashRate, self::ONE_DAY_IN_SECONDS * $number, $coinBtc)['income'] * $count;
            $data['Bitcoin'][$name]['crypto'] = $crypto;
            $data['Bitcoin'][$name]['usd'] =
                false === empty($currencies[self::DEFAULT_COIN]['quote']['USD']['price']) ?
                    $currencies[self::DEFAULT_COIN]['quote']['USD']['price'] * $crypto :
                    '';
        }

        return $data;
    }
}