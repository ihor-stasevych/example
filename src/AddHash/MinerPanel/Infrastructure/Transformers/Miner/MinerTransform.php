<?php

namespace App\AddHash\MinerPanel\Infrastructure\Transformers\Miner;

use App\AddHash\MinerPanel\Domain\Miner\Miner;
use App\AddHash\MinerPanel\Domain\Rig\Rig;
use App\AddHash\MinerPanel\Infrastructure\Transformers\Rig\RigTransform;

final class MinerTransform
{
    public function transform(Miner $miner): array
    {
    	$rig = null;

	    /** @var $rigObject Rig */
	    if (!empty($rigObject = $miner->infoRigs()->first())) {
		    $rig = [
				'id' => $rigObject->getId(),
			    'title' => $rigObject->getTitle(),
			    'url' => $rigObject->getUrl(),
			    'worker' => $rigObject->getWorker()
		    ];

		    unset($rigObject);
	    }

        return [
            'id'          => $miner->getId(),
            'title'       => $miner->getTitle(),
            'ip'          => $miner->getCredential()->getIp(),
            'port'        => $miner->getCredential()->getPort(),
            'sshPort'     => $miner->getCredential()->getPortSsh(),
            'sshLogin'    => $miner->getCredential()->getLoginSsh(),
            'hashRate'    => $miner->getHashRate(),
            'typeId'      => $miner->getType()->getId(),
            'algorithmId' => $miner->getAlgorithm()->getId(),
            'rig'         => $rig
        ];
    }
}