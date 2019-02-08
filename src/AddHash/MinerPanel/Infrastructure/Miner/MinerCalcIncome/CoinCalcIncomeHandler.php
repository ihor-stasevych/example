<?php

namespace App\AddHash\MinerPanel\Infrastructure\Miner\MinerCalcIncome;

use App\AddHash\MinerPanel\Domain\Miner\MinerAlgorithm\MinerCoin\MinerCoin;
use App\AddHash\MinerPanel\Domain\Miner\MinerCalcIncome\CoinCalcIncomeHandlerInterface;

final class CoinCalcIncomeHandler implements CoinCalcIncomeHandlerInterface
{
    private const DEFAULT_TIME = 86400;

    /**
     * Key part of class name => Coin name
     * @var array
     */
    protected $algorithmObjectTypes = [
        'SHA256' => ['SHA-256', 'Scrypt', 'X11'],
        'EtHash' => ['EtHash']
    ];

    public function handle(MinerCoin $minerCoin, float $hashRate, int $time = self::DEFAULT_TIME): float
    {
        $algorithmType = $minerCoin->infoAlgorithm()->getValue();
        $algorithmObjName = null;

        array_walk($this->algorithmObjectTypes, function ($value, $key) use ($algorithmType, &$algorithmObjName) {
            if (in_array($algorithmType, $value)) {
                $algorithmObjName = $key;
            }
        });

        $income = 0;

        if (null !== $algorithmObjName) {
            $class = __NAMESPACE__ .'\\Algorithms\\MinerCalcIncomeAlgorithm' . $algorithmObjName;
            $algorithm = new $class;

            $result = (new MinerCalcIncomeStrategy($algorithm))->execute($hashRate, $time, $minerCoin);
            $income = $result['income'];
        }

        return $income;
    }
}