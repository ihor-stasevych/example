<?php

namespace App\AddHash\MinerPanel\Infrastructure\Transformers\MinerPoolStatus;

use App\AddHash\MinerPanel\Domain\Miner\Miner;

final class MinerPoolStatusTransform
{
    public function transform(Miner $miner): array
    {
        return [
            'id'     => $miner->getId(),
            'status' => $miner->getStatusPool(),
        ];
    }
}