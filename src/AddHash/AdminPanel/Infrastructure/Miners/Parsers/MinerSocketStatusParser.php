<?php

namespace App\AddHash\AdminPanel\Infrastructure\Miners\Parsers;

class MinerSocketStatusParser extends MinerSocketParser
{
    public function normalizeData($line): bool
    {
        $status = false;

        $data = parent::normalizeData($line);

        if ($data['STATUS']['STATUS'] == 'S') {
            $status = true;
        }

        return $status;
    }
}