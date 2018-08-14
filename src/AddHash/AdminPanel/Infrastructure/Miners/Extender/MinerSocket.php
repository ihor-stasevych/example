<?php

namespace App\AddHash\AdminPanel\Infrastructure\Miners\Extender;

use App\AddHash\AdminPanel\Domain\Miners\MinerStock;
use App\AddHash\AdminPanel\Domain\Miners\Parsers\ParserInterface;
use App\AddHash\AdminPanel\Domain\Miners\Extender\MinerInterface;
use App\AddHash\AdminPanel\Domain\Miners\Exceptions\MinerSocketErrorException;
use App\AddHash\AdminPanel\Domain\Miners\Exceptions\MinerSocketCreateErrorException;
use App\AddHash\AdminPanel\Domain\Miners\Exceptions\MinerSocketConnectionErrorException;

class MinerSocket implements MinerInterface
{
    private $ip;

    private $port;

    private $parser;

    public function __construct(MinerStock $minerStock, ParserInterface $parser)
    {
        $this->ip = $minerStock->getIp();
        $this->port = $minerStock->getPort();
        $this->parser = $parser;
    }

    /**
     * @param string $cmd
     * @return array|mixed|null
     * @throws MinerSocketCreateErrorException
     */
    public function request(string $cmd)
    {
        try {
            $socket = $this->getSocket($this->ip, $this->port);
            socket_write($socket, $cmd, strlen($cmd));
            $line = $this->readSocketLine($socket);
            socket_close($socket);
        } catch (MinerSocketErrorException $e) {
            $line = '';
        }

        return $this->parser->normalizeData($line);
    }

    /**
     * @param string $address
     * @param string $port
     * @return resource
     * @throws MinerSocketConnectionErrorException
     * @throws MinerSocketCreateErrorException
     */
    private function getSocket(string $address, string $port)
    {
        $socket = @socket_create(AF_INET, SOCK_STREAM, SOL_TCP);

        if (false === $socket) {
            $error = socket_strerror(socket_last_error());
            throw new MinerSocketCreateErrorException("ERR: socket create(TCP) failed " . $error);
        }

        $connection = @socket_connect($socket, $address, $port);

        if (false === $connection) {
            socket_close($socket);
            $error = socket_strerror(socket_last_error());
            throw new MinerSocketConnectionErrorException("ERR: socket connect (" . $address . "," . $port . ") failed " . $error);
        }

        return $socket;
    }

    /**
     * @param $socket
     * @return string
     */
    private function readSocketLine($socket): string
    {
        $line = '';

        while (true) {
            $byte = socket_read($socket, 1);

            if (false === $byte || $byte === '') {
                break;
            }

            if ($byte === "\0") {
                break;
            }

            $line .= $byte;
        }

        return $line;
    }
}