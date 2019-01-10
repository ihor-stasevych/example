<?php

namespace App\AddHash\MinerPanel\Infrastructure\Miner\MinerCalcIncome;

use App\AddHash\MinerPanel\Domain\Miner\Miner;
use App\AddHash\MinerPanel\Domain\Miner\MinerAlgorithm\MinerCoin\MinerCoin;
use App\AddHash\MinerPanel\Domain\Miner\MinerCalcIncome\MinerCalcIncomeHandlerInterface;


final class MinerCalcIncomeHandler implements MinerCalcIncomeHandlerInterface
{
	/**
	 * Key part of class name => Coin name
	 * @var array
	 */
	protected $algorithmObjectTypes = [
		'SHA256' => ['SHA-256', 'Scrypt', 'X11'],
		'EtHash' => ['EtHash']
	];

	/**
	 * @param Miner $miner
	 * @param int $time
	 * @return array
	 * @throws \Exception
	 */
    public function handler(Miner $miner, int $time = 86400): array
    {
        $data = [];
        $coins = $miner->getAlgorithm()->getCoins();

        if (empty($coins->count())) {
        	return $data;
        }

        $algorithmType = $miner->getAlgorithm()->getValue();
        $algorithmObjName = false;

	    /**
	     * Search part of class name by algorithm type
	     */
	    array_walk($this->algorithmObjectTypes, function ($value, $key) use ($algorithmType, &$algorithmObjName) {
			if (in_array($algorithmType, $value)) {
				$algorithmObjName = $key;
			}
        });

        if ($algorithmObjName){
        	$class = __NAMESPACE__ .'\\Algorithms\\MinerCalcIncomeAlgorithm' . $algorithmObjName;
	        $algorithm = new $class;

	        /** @var MinerCoin $coin */
	        foreach ($coins as $coin) {
		        $data[] = (new MinerCalcIncomeStrategy($algorithm))->execute($miner->getHashRate(), $time, $coin);
	        }
        }

        return $data;
    }
}