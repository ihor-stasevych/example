<?php

namespace App\AddHash\AdminPanel\Domain\Miners\SSH2;

interface SSH2SCPInterface
{
    public function fetch(string $localPath, string $remotePath);

    public function send(string $localPath, string $remotePath);
}