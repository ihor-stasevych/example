<?php

namespace App\AddHash\MinerPanel\Infrastructure\Services\Miner\MinerAlgorithm\MinerCoin;

use App\AddHash\MinerPanel\Domain\Miner\MinerAlgorithm\MinerAlgorithm;
use App\AddHash\MinerPanel\Domain\Miner\MinerAlgorithm\MinerAlgorithmRepositoryInterface;
use App\AddHash\MinerPanel\Domain\Miner\MinerAlgorithm\MinerCoin\Exceptions\MinerCoinInfoGetInvalidOutputDataException;
use App\AddHash\MinerPanel\Domain\Miner\MinerAlgorithm\MinerCoin\Exceptions\MinerCoinInvalidIconException;
use App\AddHash\MinerPanel\Domain\Miner\MinerAlgorithm\MinerCoin\Exceptions\MinerCoinNoChangePermissionIconDirException;
use App\AddHash\MinerPanel\Domain\Miner\MinerAlgorithm\MinerCoin\Exceptions\MinerCoinNoCreateDirIconException;
use App\AddHash\MinerPanel\Domain\Miner\MinerAlgorithm\MinerCoin\MinerCoin;
use App\AddHash\MinerPanel\Domain\Miner\MinerAlgorithm\MinerCoin\MinerCoinRepositoryInterface;
use App\AddHash\MinerPanel\Domain\Miner\MinerAlgorithm\MinerCoin\Services\MinerCoinInfoGetServiceInterface;

final class MinerCoinInfoGetService implements MinerCoinInfoGetServiceInterface
{
    private const API_URL = 'https://www.coinwarz.com/v1/api/profitability/?apikey=%s&algo=all';

    private const ICON_URL = 'https://www.coinwarz.com/content/images/';

    private const ICON_DIR_PATH = '/../public/images/icon/coins/';

    private const EXTENSION_ICON = '.png';


    private $minerAlgorithmRepository;

    private $minerCoinRepository;

    private $apiKey;

    private $iconDir;

    public function __construct(
        MinerAlgorithmRepositoryInterface $minerAlgorithmRepository,
        MinerCoinRepositoryInterface $mineCoinRepository,
        $apiKey,
        $rootDir
    )
    {
        $this->minerAlgorithmRepository = $minerAlgorithmRepository;
        $this->minerCoinRepository = $mineCoinRepository;
        $this->apiKey = $apiKey;
        $this->iconDir = $rootDir . static::ICON_DIR_PATH;
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
            $icon = $this->getIcon($coinInfo['CoinName']);

            if (null === $minerCoin) {
                $minerCoin = new MinerCoin(
                    $coinInfo['CoinName'],
                    $coinInfo['CoinTag'],
                    $icon,
                    $coinInfo['Difficulty'],
                    $coinInfo['BlockReward'],
                    $this->getOrNewMinerAlgorithm($coinInfo['Algorithm'])
                );
            } else {
                $minerCoin->setIcon($icon);
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

        if (true === is_array($coinInfo)) {
            $coinInfoKeys = array_keys($coinInfo);
            $isValid = empty(array_diff($requiredKeys, $coinInfoKeys));
        }

        return $isValid;
    }

    private function getIcon(string $coinName): string
    {
        $iconName = $coinName . static::EXTENSION_ICON;

        $iconPath = $this->iconDir . $iconName;

        if (false === file_exists($iconPath)) {
            try {
                $this->saveIcon(
                    $this->getContentIcon($coinName),
                    $iconPath
                );
            } catch (\Exception $e) {
                $iconName = '';
            }
        }

        return $iconName;
    }

    /**
     * @param $coinName
     * @return string
     * @throws MinerCoinInvalidIconException
     */
    private function getContentIcon($coinName): string
    {
        $content = file_get_contents(static::ICON_URL . $coinName . '-64x64' . static::EXTENSION_ICON);

        if (true === empty($content)) {
            throw new MinerCoinInvalidIconException('Invalid url icon');
        }

        return $content;
    }

    /**
     * @param string $content
     * @param string $iconPath
     * @throws MinerCoinNoChangePermissionIconDirException
     * @throws MinerCoinNoCreateDirIconException
     */
    private function saveIcon(string $content, string $iconPath): void
    {
        if (false === is_dir($this->iconDir)) {
            $isDirCreate = mkdir($this->iconDir, 0777, true);

            if (false === $isDirCreate) {
                throw new MinerCoinNoCreateDirIconException('No create dir icon');
            }
        } else {
            if (false === is_writable($this->iconDir)) {
                $isChangePermission = chmod($this->iconDir, 0777);

                if (false === $isChangePermission) {
                    throw new MinerCoinNoChangePermissionIconDirException('No change permission icon dir');
                }
            }
        }

        $fp = fopen($iconPath, "w");
        fwrite($fp, $content);
        fclose($fp);
    }
}