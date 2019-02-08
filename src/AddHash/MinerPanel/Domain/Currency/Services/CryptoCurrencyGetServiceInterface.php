<?php

namespace App\AddHash\MinerPanel\Domain\Currency\Services;

interface CryptoCurrencyGetServiceInterface
{
    public function execute(): array;
}