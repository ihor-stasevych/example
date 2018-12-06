<?php

namespace App\AddHash\MinerPanel\Domain\Miner\Parsers;

interface ParserInterface
{
    public function normalizeData(string $line): array;
}