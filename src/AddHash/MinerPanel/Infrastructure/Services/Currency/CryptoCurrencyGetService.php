<?php

namespace App\AddHash\MinerPanel\Infrastructure\Services\Currency;

use App\AddHash\System\Lib\Cache\CacheInterface;
use App\AddHash\MinerPanel\Domain\Currency\Services\CryptoCurrencyGetServiceInterface;
use App\AddHash\MinerPanel\Domain\Currency\Exceptions\CryptoCurrencyGetInvalidApiResponseException;

final class CryptoCurrencyGetService implements CryptoCurrencyGetServiceInterface
{
    private const URL = 'https://pro-api.coinmarketcap.com/v1/cryptocurrency/listings/latest?limit=' . self::LIMIT_COUNT_RESULT;

    private const CACHE_KEY = 'cryptoCurrencies';

    private const LIMIT_COUNT_RESULT = 5000;


    private $cache;

    private $apKey;

    public function __construct(CacheInterface $cache, string $apKey)
    {
        $this->cache = $cache;
        $this->apKey = $apKey;
    }

    /**
     * @return array
     * @throws CryptoCurrencyGetInvalidApiResponseException
     */
    public function execute(): array
    {
        if (false === $this->isKeyExist()) {
            $currencies = $this->getCurrencies();
            $this->cache->add(self::CACHE_KEY, $currencies);
        } else {
            $currencies = $this->cache->getKey(self::CACHE_KEY);
        }

        return $currencies;
    }

    private function isKeyExist(): bool
    {
        return $this->cache->keyExists(self::CACHE_KEY);
    }

    /**
     * @return array
     * @throws CryptoCurrencyGetInvalidApiResponseException
     */
    private function getCurrencies(): array
    {
        $options = [
            'http'=> [
                'method' => 'GET',
                'header' => 'X-CMC_PRO_API_KEY: ' . $this->apKey,
            ],
        ];
        $context = stream_context_create($options);

        $response = file_get_contents(self::URL, false, $context);

        if (true === empty($response)) {
            throw new CryptoCurrencyGetInvalidApiResponseException('Invalid response');
        }

        $response = json_decode($response, true);
        $currencies = $response['data'];

        if (true === empty($currencies)) {
            throw new CryptoCurrencyGetInvalidApiResponseException('Invalid response');
        }

        return $this->formattingCurrencies($currencies);
    }

    private function formattingCurrencies(array $currencies): array
    {
        $data = [];

        foreach ($currencies as $currency) {
            $data[$currency['symbol']] = $currency;
        }

        return $data;
    }
}