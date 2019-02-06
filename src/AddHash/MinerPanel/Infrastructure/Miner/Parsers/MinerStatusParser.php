<?php

namespace App\AddHash\MinerPanel\Infrastructure\Miner\Parsers;

class MinerStatusParser extends Parser
{
    const STATUS_OFF = 0;

    const STATUS_ON = 1;

    public function normalizeData(string $line): array
    {
        $data = parent::normalizeData($line);

        $result = [];

        $result['status'] = (true === empty($data)) ? self::STATUS_OFF : self::STATUS_ON;

        return $result;
    }
}