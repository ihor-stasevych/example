<?php

namespace App\AddHash\MinerPanel\Infrastructure\Miner\Extender;

use App\AddHash\MinerPanel\Domain\Miner\Extender\MinerSocketInterface;
use App\AddHash\MinerPanel\Domain\Miner\MinerCredential\MinerCredential;
use App\AddHash\MinerPanel\Domain\Miner\Exceptions\MinerSocketCreateErrorException;
use App\AddHash\MinerPanel\Domain\Miner\Exceptions\MinerSocketConnectionErrorException;

class MinerSocket implements MinerSocketInterface
{
    private const TIMEOUT_SOCKET_CONNECTION = 5;


    private $ip;

    private $port;

    public function __construct(MinerCredential $minerCredential)
    {
        $this->ip = $minerCredential->getIp();
        $this->port = $minerCredential->getPort();
    }

    /**
     * @param string $cmd
     * @return string
     */
    public function request(string $cmd): string
    {
        try {
            $socket = $this->getSocket($this->ip, $this->port);
            socket_write($socket, $cmd, strlen($cmd));
            $line = $this->readSocketLine($socket);
            socket_close($socket);
        } catch (\Exception $e) {
            $line = '';
        }

        return $line;
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

        socket_set_option($socket, SOL_SOCKET, SO_RCVTIMEO, [
            'sec'  => self::TIMEOUT_SOCKET_CONNECTION,
            'usec' => 0,
        ]);

        socket_set_option($socket, SOL_SOCKET, SO_SNDTIMEO, [
            'sec'  => self::TIMEOUT_SOCKET_CONNECTION,
            'usec' => 0,
        ]);

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