<?php

namespace App\AddHash\AdminPanel\Infrastructure\Miners\Parsers;

class MinerSocketCountPoolsParser extends MinerSocketParser
{
    public function normalizeData($line): int
    {
        $count = 0;

        $data = parent::normalizeData($line);

        if (!empty($data['POOL']) && is_array($data['POOL'])) {
            $count = count($data['POOL']);
        }

        return $count;
    }
}