<?php

namespace App\AddHash\MinerPanel\Infrastructure\Miner\Parsers;

class MinerPoolsParser extends Parser
{
    public function normalizeData(string $line): array
    {
        $data = parent::normalizeData($line);

        $result = [
            'pools' => []
        ];

        if (false === empty($data['POOL']) && true === is_array($data['POOL'])) {
            foreach ($data['POOL'] as $pool) {
                $result['pools'][] = [
                    'url'      => (false === empty($pool['URL'])) ? $pool['URL'] : '',
                    'user'     => (false === empty($pool['User'])) ? $pool['User'] : '',
                    'status'   => (false === empty($pool['Status'])) ? $pool['Status'] : '',
                    'accepted' => (false === empty($pool['Accepted'])) ? $pool['Accepted'] : '',
                    'rejected' => (false === empty($pool['Rejected'])) ? $pool['Rejected'] : '',
                ];
            }
        }

        return $result;
    }
}