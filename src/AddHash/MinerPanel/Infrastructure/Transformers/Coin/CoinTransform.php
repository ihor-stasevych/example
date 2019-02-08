<?php

namespace App\AddHash\MinerPanel\Infrastructure\Transformers\Coin;

use App\AddHash\MinerPanel\Domain\Miner\MinerAlgorithm\MinerCoin\MinerCoin;

final class CoinTransform
{
    private const ICON_PATH = '/images/icon/coins/';

    public function transform(MinerCoin $minerCoin, string $hostName): array
    {
        return [
            'id'         => $minerCoin->getId(),
            'name'       => $minerCoin->getName(),
            'tag'        => $minerCoin->getTag(),
            'algorithm'  => $minerCoin->infoAlgorithm()->getValue(),
            'reward'     => $minerCoin->getReward(),
            'difficulty' => $minerCoin->getDifficulty(),
            'icon'       => $hostName . self::ICON_PATH . $minerCoin->getIcon(),
        ];
    }
}