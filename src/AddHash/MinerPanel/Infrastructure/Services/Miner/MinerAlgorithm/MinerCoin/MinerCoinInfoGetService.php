<?php

namespace App\AddHash\MinerPanel\Infrastructure\Services\Miner\MinerAlgorithm\MinerCoin;

use App\AddHash\MinerPanel\Domain\Miner\MinerAlgorithm\MinerAlgorithm;
use App\AddHash\MinerPanel\Domain\Miner\MinerAlgorithm\MinerCoin\MinerCoin;
use App\AddHash\MinerPanel\Domain\Miner\MinerAlgorithm\MinerAlgorithmRepositoryInterface;
use App\AddHash\MinerPanel\Domain\Miner\MinerAlgorithm\MinerCoin\MinerCoinRepositoryInterface;
use App\AddHash\MinerPanel\Domain\Miner\MinerAlgorithm\MinerCoin\Services\MinerCoinInfoGetServiceInterface;
use App\AddHash\MinerPanel\Domain\Miner\MinerAlgorithm\MinerCoin\Exceptions\MinerCoinInfoGetInvalidOutputDataException;

final class MinerCoinInfoGetService implements MinerCoinInfoGetServiceInterface
{
    private const API_URL = 'https://www.coinwarz.com/v1/api/profitability/?apikey=%s&algo=all';


    private $minerAlgorithmRepository;

    private $minerCoinRepository;

    private $apiKey;

    public function __construct(
        MinerAlgorithmRepositoryInterface $minerAlgorithmRepository,
        MinerCoinRepositoryInterface $mineCoinRepository,
        $apiKey
    )
    {
        $this->minerAlgorithmRepository = $minerAlgorithmRepository;
        $this->minerCoinRepository = $mineCoinRepository;
        $this->apiKey = $apiKey;
    }

    /**
     * @throws MinerCoinInfoGetInvalidOutputDataException
     */
    public function execute(): void
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, sprintf(static::API_URL, $this->apiKey));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        $output = curl_exec($ch);
        curl_close($ch);

        if (true === empty($output)) {
            throw new MinerCoinInfoGetInvalidOutputDataException('Invalid output data');
        }

        /** Fix fucking incorrect json */
        $output = str_replace('NaN', '"NaN"', $output);

        $output = json_decode($output, true);

        if (true === empty($output['Success']) || true != $output['Success'] || true === empty($output['Data'])) {
            throw new MinerCoinInfoGetInvalidOutputDataException('Invalid output data');
        }

        $coinsInfo = $output['Data'];

        foreach ($coinsInfo as $coinInfo) {
            if (false === $this->isValidArrayCoinInfo($coinInfo)) {
                continue;
            }

            $minerCoin = $this->minerCoinRepository->getByTag($coinInfo['CoinTag']);

            if (null === $minerCoin) {
                $minerCoin = new MinerCoin(
                    $coinInfo['CoinName'],
                    $coinInfo['CoinTag'],
                    $coinInfo['Difficulty'],
                    $coinInfo['BlockReward'],
                    $this->getOrNewMinerAlgorithm($coinInfo['Algorithm'])
                );
            } else {
                $minerCoin->setDifficulty($coinInfo['Difficulty']);
                $minerCoin->setReward($coinInfo['BlockReward']);
                $minerCoin->setUpdateAt();
            }

            $this->minerCoinRepository->save($minerCoin);
        }
    }

    private function getOrNewMinerAlgorithm(string $value): MinerAlgorithm
    {
        $minerAlgorithm = $this->minerAlgorithmRepository->getByValue($value);

        if (null === $minerAlgorithm) {
            $minerAlgorithm = new MinerAlgorithm($value);

            $this->minerAlgorithmRepository->save($minerAlgorithm);
        }

        return $minerAlgorithm;
    }

    private function isValidArrayCoinInfo($coinInfo): bool
    {
        $requiredKeys = ['CoinName', 'CoinTag', 'Difficulty', 'BlockReward', 'Algorithm'];

        $isValid = false;

        if (is_array($coinInfo)) {
            $coinInfoKeys = array_keys($coinInfo);
            $isValid = empty(array_diff($requiredKeys, $coinInfoKeys));
        }

        return $isValid;
    }
}