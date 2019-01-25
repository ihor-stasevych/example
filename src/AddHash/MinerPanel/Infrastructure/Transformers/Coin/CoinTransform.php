<?php

namespace App\AddHash\MinerPanel\Infrastructure\Transformers\Coin;

use App\AddHash\MinerPanel\Domain\Miner\MinerAlgorithm\MinerCoin\MinerCoin;

final class CoinTransform
{
    public function transform(MinerCoin $minerCoin): array
    {
        return [
            'id'         => $minerCoin->getId(),
            'name'       => $minerCoin->getName(),
            'tag'        => $minerCoin->getTag(),
            'algorithm'  => $minerCoin->infoAlgorithm()->getValue(),
            'reward'     => $minerCoin->getReward(),
            'difficulty' => $minerCoin->getDifficulty(),
            'icon'       => $minerCoin->getIcon(),
        ];
    }
}