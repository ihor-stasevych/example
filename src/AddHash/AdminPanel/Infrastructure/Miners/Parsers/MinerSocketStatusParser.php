<?php

namespace App\AddHash\AdminPanel\Infrastructure\Miners\Parsers;

class MinerSocketStatusParser extends MinerSocketParser
{
    private const STATUS_SUCCESS = 'S';

    public function normalizeData($line): bool
    {
        $status = false;

        $data = parent::normalizeData($line);

        if ($data['STATUS']['STATUS'] == static::STATUS_SUCCESS) {
            $status = true;
        }

        return $status;
    }
}