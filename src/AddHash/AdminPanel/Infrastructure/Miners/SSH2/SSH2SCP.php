<?php

namespace App\AddHash\AdminPanel\Infrastructure\Miners\SSH2;

use App\AddHash\AdminPanel\Domain\Miners\SSH2\SSH2SCPInterface;
use App\AddHash\AdminPanel\Domain\Miners\SSH2\Exceptions\SSH2SCPFailException;

class SSH2SCP implements SSH2SCPInterface
{
    private $connection;

    public function __construct($connection)
    {
        $this->connection = $connection;
    }

    /**
     * @param string $localPath
     * @param string $remotePath
     * @throws SSH2SCPFailException
     */
    public function fetch(string $localPath, string $remotePath)
    {
        $result = @ssh2_scp_recv($this->connection, $remotePath, $localPath);

        $this->scpException($result);
    }

    /**
     * @param string $localPath
     * @param string $remotePath
     * @throws SSH2SCPFailException
     */
    public function send(string $localPath, string $remotePath)
    {
        $result = @ssh2_scp_send($this->connection, $localPath, $remotePath);

        $this->scpException($result);
    }

    /**
     * @param bool $result
     * @throws SSH2SCPFailException
     */
    private function scpException(bool $result)
    {
        if (false === $result) {
            throw new SSH2SCPFailException('SCP fail');
        }
    }
}