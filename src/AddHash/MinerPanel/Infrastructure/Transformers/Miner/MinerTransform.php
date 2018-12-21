<?php

namespace App\AddHash\MinerPanel\Infrastructure\Transformers\Miner;

use App\AddHash\MinerPanel\Domain\Miner\Miner;

final class MinerTransform
{
    public function transform(Miner $miner): array
    {
        $rig = (false !== $miner->infoRigs()->first()) ?
            $miner->infoRigs()->first()->getId() :
            null;

        return [
            'id'          => $miner->getId(),
            'title'       => $miner->getTitle(),
            'ip'          => $miner->getCredential()->getIp(),
            'port'        => $miner->getCredential()->getPort(),
            'hashRate'    => $miner->getHashRate(),
            'typeId'      => $miner->getType()->getId(),
            'algorithmId' => $miner->getAlgorithm()->getId(),
            'rigId'       => $rig,
        ];
    }
}