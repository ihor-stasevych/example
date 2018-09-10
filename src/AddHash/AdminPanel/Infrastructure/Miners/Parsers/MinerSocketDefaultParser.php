<?php

namespace App\AddHash\AdminPanel\Infrastructure\Miners\Parsers;

use App\AddHash\AdminPanel\Domain\Miners\Parsers\ParserInterface;

class MinerSocketDefaultParser implements ParserInterface
{
    public function normalizeData($line)
    {
        return $line;
    }
}