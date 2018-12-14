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

        if ($miners->count() > 0) {
            /** @var Miner $miner */
            foreach ($miners as $miner) {
                $minersData[] = [
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

        return [
            'id'     => $rig->getId(),
            'title'  => $rig->getTitle(),
            'worker' => $rig->getWorker(),
            'url'    => $rig->getUrl(),
            'miners' => $minersData,
        ];
    }
}