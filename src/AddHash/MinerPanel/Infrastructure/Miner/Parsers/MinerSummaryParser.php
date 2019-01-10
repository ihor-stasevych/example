<?php

namespace App\AddHash\MinerPanel\Infrastructure\Miner\Parsers;

class MinerSummaryParser extends Parser
{
    public function normalizeData(string $line): array
    {
        $data = parent::normalizeData($line);

        $result = [];

        $result['status'] = (false === empty($data['STATUS']['STATUS'])) ? $data['STATUS']['STATUS'] : '';

        $result['accepted'] = (false === empty($data['SUMMARY']['Accepted'])) ? $data['SUMMARY']['Accepted'] : '';

        $result['rejected'] = (false === empty($data['SUMMARY']['Rejected'])) ? $data['SUMMARY']['Rejected'] : '';

        $result['hashRate'] = (false === empty($data['SUMMARY']['GHS 5s'])) ? $data['SUMMARY']['GHS 5s'] : '';

        $result['hashRateAverage'] = (false === empty($data['SUMMARY']['GHS av'])) ? $data['SUMMARY']['GHS av'] : '';

        return $result;
    }
}