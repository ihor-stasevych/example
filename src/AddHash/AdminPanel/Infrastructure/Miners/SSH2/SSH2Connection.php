<?php

namespace App\AddHash\AdminPanel\Infrastructure\Miners\SSH2;

use App\AddHash\AdminPanel\Domain\Miners\SSH2\SSH2ConnectionInterface;
use App\AddHash\AdminPanel\Domain\Miners\SSH2\Exceptions\SSH2ConnectionFailException;

class SSH2Connection implements SSH2ConnectionInterface
{
    private $host;

    private $port;

    public function __construct(string $host, int $port = 22)
    {
        $this->host = $host;
        $this->port = $port;
    }

    /**
     * @return resource
     * @throws SSH2ConnectionFailException
     */
    public function getConnection()
    {
        $connection = @ssh2_connect($this->host, $this->port);

        if (false === $connection) {
            throw new SSH2ConnectionFailException('Connection fail');
        }

        return $connection;
    }
}