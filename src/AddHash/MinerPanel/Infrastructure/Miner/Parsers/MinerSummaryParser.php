<?php

namespace App\AddHash\MinerPanel\Infrastructure\Miner\Parsers;

use App\AddHash\MinerPanel\Domain\Miner\Parsers\ParserInterface;

class MinerSummaryParser extends Parser implements ParserInterface
{
    private const STATUS_SUCCESS = 'S';

    public function normalizeData(string $line): array
    {
        $data = parent::normalizeData($line);

        $result = [];

        $result['status'] = (
            false === empty($data['STATUS']['STATUS']) &&
            $data['STATUS']['STATUS'] == self::STATUS_SUCCESS
        ) ? true : false;

        $result['accepted'] = (false === empty($data['SUMMARY']['Accepted'])) ? $data['SUMMARY']['Accepted'] : '';

        $result['rejected'] = (false === empty($data['SUMMARY']['Rejected'])) ? $data['SUMMARY']['Rejected'] : '';

        return $result;
    }
}