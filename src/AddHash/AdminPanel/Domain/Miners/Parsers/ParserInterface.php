<?php

namespace App\AddHash\AdminPanel\Domain\Miners\Parsers;

interface ParserInterface
{
    public function normalizeData($data): array;
}