<?php

namespace App\AddHash\MinerPanel\Infrastructure\Miner\Parsers;

class MinerSummaryParser extends Parser
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

        $result['hashRate'] = (false === empty($data['SUMMARY']['GHS 5s'])) ? $data['SUMMARY']['GHS 5s'] : '';

        $result['hashRateAverage'] = (false === empty($data['SUMMARY']['GHS av'])) ? $data['SUMMARY']['GHS av'] : '';

        return $result;
    }
}