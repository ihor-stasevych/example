<?php

namespace App\AddHash\MinerPanel\Infrastructure\Miner\MinerCalcIncome;

use App\AddHash\MinerPanel\Domain\Miner\Miner;
use App\AddHash\MinerPanel\Domain\Miner\MinerAlgorithm\MinerCoin\MinerCoin;
use App\AddHash\MinerPanel\Domain\Miner\MinerCalcIncome\MinerCalcIncomeHandlerInterface;
use App\AddHash\MinerPanel\Infrastructure\Miner\MinerCalcIncome\Algorithms\MinerCalcIncomeAlgorithmEtHash;
use App\AddHash\MinerPanel\Infrastructure\Miner\MinerCalcIncome\Algorithms\MinerCalcIncomeAlgorithmSHA256;
use App\AddHash\MinerPanel\Infrastructure\Miner\MinerCalcIncome\Algorithms\MinerCalcIncomeAlgorithmDefault;

final class MinerCalcIncomeHandler implements MinerCalcIncomeHandlerInterface
{
    public function handler(Miner $miner, int $time = 86400): array
    {
        $data = [];
        $coins = $miner->getAlgorithm()->getCoin();

        if ($coins->count() > 0) {
            $algorithm = $miner->getAlgorithm()->getValue();

            /** @var MinerCoin $coin */
            foreach ($coins as $coin) {
                switch ($algorithm) {
                    case 'SHA-256':
                    case 'Scrypt':
                    case 'X11':
                        $algorithmCalc = new MinerCalcIncomeAlgorithmSHA256();
                        break;
                    case 'EtHash':
                        $algorithmCalc = new MinerCalcIncomeAlgorithmEtHash();
                        break;
                    default:
                        $algorithmCalc = new MinerCalcIncomeAlgorithmDefault();
                }

                $data[] = (new MinerCalcIncomeStrategy($algorithmCalc))->execute($miner->getHashRate(), $time, $coin);
            }
        }

        return $data;
    }
}