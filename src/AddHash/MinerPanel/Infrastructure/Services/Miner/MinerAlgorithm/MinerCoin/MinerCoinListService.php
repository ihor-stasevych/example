<?php

namespace App\AddHash\MinerPanel\Infrastructure\Services\Miner\MinerAlgorithm\MinerCoin;

use App\AddHash\System\Response\ResponseListCollection;
use App\AddHash\MinerPanel\Domain\Miner\MinerRepositoryInterface;
use App\AddHash\MinerPanel\Infrastructure\Transformers\Coin\CoinTransform;
use App\AddHash\AdminPanel\Domain\Info\Services\GetCryptoCurrenciesServiceInterface;
use App\AddHash\MinerPanel\Domain\User\Services\UserAuthenticationGetServiceInterface;
use App\AddHash\MinerPanel\Domain\Miner\MinerHashRate\MinerHashRateRepositoryInterface;
use App\AddHash\MinerPanel\Domain\Miner\MinerCalcIncome\MinerCalcIncomeHandlerInterface;
use App\AddHash\MinerPanel\Domain\Miner\MinerAlgorithm\MinerCoin\MinerCoinRepositoryInterface;
use App\AddHash\MinerPanel\Domain\Miner\MinerAlgorithm\MinerCoin\Command\MinerCoinListCommandInterface;
use App\AddHash\MinerPanel\Domain\Miner\MinerAlgorithm\MinerCoin\Services\MinerCoinListServiceInterface;

final class MinerCoinListService implements MinerCoinListServiceInterface
{
    private $authenticationAdapter;

    private $calcIncomeHandler;

    private $coinRepository;

    private $getCryptoCurrenciesService;

    private $hashRateRepository;

    private $minerRepository;

    private $cryptoToUsd = [];

    private $hashRate = [];

    public function __construct(
        UserAuthenticationGetServiceInterface $authenticationAdapter,
        MinerCalcIncomeHandlerInterface $calcIncomeHandler,
        MinerCoinRepositoryInterface $coinRepository,
        GetCryptoCurrenciesServiceInterface $getCryptoCurrenciesService,
        MinerHashRateRepositoryInterface $hashRateRepository,
        MinerRepositoryInterface $minerRepository
    )
    {
        $this->authenticationAdapter = $authenticationAdapter;
        $this->calcIncomeHandler = $calcIncomeHandler;
        $this->coinRepository = $coinRepository;
        $this->getCryptoCurrenciesService = $getCryptoCurrenciesService;
        $this->hashRateRepository = $hashRateRepository;
        $this->minerRepository = $minerRepository;
    }

    public function execute(MinerCoinListCommandInterface $command): ResponseListCollection
    {
        $coins = $this->coinRepository->getCoinsWithPagination($command->page());

        $data = [];

        if ($coins->count() > 0) {
            $transform = new CoinTransform();

            foreach ($coins as $coin) {
                $cryptoToUsd = $this->getCryptoToUsd($coin->getTag());
                $result = $transform->transform($coin);

                $data[] = $result + [
                    'cryptoToUsd' => $cryptoToUsd,
                    'hashRate'    => $this->getHashRate($coin->infoAlgorithm()->getId()),
                ];
            }
        }

        return new ResponseListCollection(
            $data,
            $coins->getNbResults(),
            $coins->getNbPages(),
            $coins->getCurrentPage(),
            $coins->getMaxPerPage()
        );
    }

    private function getCryptoToUsd(string $tag): string
    {
        if (true === empty($this->cryptoToUsd)) {
            $cryptoCurrencies = $this->getCryptoCurrenciesService->execute();
            $cryptoCurrencies = $cryptoCurrencies['data'];

            foreach ($cryptoCurrencies as $cryptoCurrency) {
                $this->cryptoToUsd[$cryptoCurrency['symbol']] = $cryptoCurrency['quotes']['USD']['price'];
            }
        }

        return true === isset($this->cryptoToUsd[$tag]) ? $this->cryptoToUsd[$tag] : '';
    }

    private function getHashRate(int $algorithmId): string
    {
        if (true === empty($this->hashRate)) {
            $user = $this->authenticationAdapter->execute();

            $items = $this->minerRepository->getAvgHashRateActiveMinersByUserGroupByAlgorithm($user);

            foreach ($items as $item) {
                $this->hashRate[$item['miner']->getAlgorithm()->getId()] = $item['hashRateAvg'];
            }
        }

        return true === isset($this->hashRate[$algorithmId]) ? $this->hashRate[$algorithmId] : '';
    }
}