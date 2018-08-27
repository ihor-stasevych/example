<?php

namespace App\AddHash\AdminPanel\Infrastructure\Miners\Parsers;

class MinerSocketStatusParser extends MinerSocketParser
{
    private const STATUS_SUCCESS_API = 'S';

    const STATUS_SUCCESS = 1;

    const STATUS_ERROR = 0;

    public function normalizeData($line): array
    {
        $status = static::STATUS_ERROR;

        $data = parent::normalizeData($line);

        if ($data['STATUS']['STATUS'] == static::STATUS_SUCCESS_API) {
            $status = static::STATUS_SUCCESS;
        }

        return [
            'status' => $status,
            'data'   => $data,
        ];
    }
}