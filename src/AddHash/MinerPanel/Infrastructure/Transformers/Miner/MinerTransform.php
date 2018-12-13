<?php

namespace App\AddHash\MinerPanel\Infrastructure\Transformers\Miner;

use App\AddHash\MinerPanel\Domain\Miner\Miner;

final class MinerTransform
{
    public function transform(Miner $miner): array
    {
        return [
            'id'        => $miner->getId(),
            'title'     => $miner->getTitle(),
            'ip'        => $miner->getIp(),
            'port'      => $miner->getPort(),
            'hashRate'  => $miner->getHashRate(),
            'type'      => $miner->getType()->getValue(),
            'algorithm' => $miner->getAlgorithm()->getValue()
        ];
    }
}