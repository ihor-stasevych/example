<?php

namespace App\AddHash\MinerPanel\Infrastructure\Transformers\Rig;

use App\AddHash\MinerPanel\Domain\Rig\Rig;
use App\AddHash\MinerPanel\Domain\Miner\Miner;

final class RigTransform
{
    public function transform(Rig $rig): array
    {
        $miners = $rig->getMiners();
        $minersData = [];

        /** @var Miner $miner */
        foreach ($miners as $miner) {
            $minersData[] = [
                'id'          => $miner->getId(),
                'title'       => $miner->getTitle(),
                'ip'          => $miner->getCredential()->getIp(),
                'port'        => $miner->getCredential()->getPort(),
                'hashRate'    => $miner->getHashRate(),
                'typeId'      => $miner->getType()->getId(),
                'algorithmId' => $miner->getAlgorithm()->getId(),
            ];
        }

        return [
            'id'     => $rig->getId(),
            'title'  => $rig->getTitle(),
            'worker' => $rig->getWorker(),
            'url'    => $rig->getUrl(),
            'miners' => $minersData,
        ];
    }
}