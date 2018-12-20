<?php

namespace App\AddHash\MinerPanel\Infrastructure\Transformers\Miner;

use App\AddHash\MinerPanel\Domain\Miner\Miner;

final class MinerAllTransform
{
    public function transform(Miner $miner): array
    {
        return [
            'id'    => $miner->getId(),
            'title' => $miner->getTitle(),
        ];
    }
}