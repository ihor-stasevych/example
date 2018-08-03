<?php

namespace App\AddHash\AdminPanel\Infrastructure\Miners\Extender;

use App\AddHash\AdminPanel\Domain\Miners\Miner;
use App\AddHash\AdminPanel\Domain\Miners\Extender\MinerSocketInterface;
use App\AddHash\AdminPanel\Domain\Miners\Exceptions\MinerSocketErrorException;
use App\AddHash\AdminPanel\Domain\Miners\Exceptions\MinerSocketCreateErrorException;
use App\AddHash\AdminPanel\Domain\Miners\Exceptions\MinerSocketConnectionErrorException;

class MinerSocket implements MinerSocketInterface
{
    private $ip;

    private $port;

    public function __construct(Miner $miner)
    {
        $this->ip = $miner->getIp();
        $this->port = $miner->getPort();
    }

    /**
     * @return array|mixed|null
     * @throws MinerSocketCreateErrorException
     */
    public function request()
    {
        try {
            $socket = $this->getSocket($this->ip, $this->port);
            $line = $this->readSocketLine($socket);
            socket_close($socket);
        } catch (MinerSocketErrorException $e) {
            $line = '';
        }

        return $this->getData($line);
    }

    /**
     * Parsing data
     *
     * @param $line
     * @return array|mixed|null
     */
    private function getData($line)
    {
        $data = null;

        if (strlen($line) != 0) {

            if (substr($line, 0, 1) == '{') {
                $data = json_decode($line, true);
            } else {
                $data = [];
                $objects = explode('|', $line);

                foreach ($objects as $object) {

                    if (strlen($object) > 0) {
                        $items = explode(',', $object);

                        $id = explode('=', $items[0], 2);

                        if (count($id) == 1 || !ctype_digit($id[1])) {
                            $name = $id[0];
                        } else {
                            $name = $id[0] . $id[1];
                        }

                        if (strlen($name) == 0) {
                            $name = 'null';
                        }

                        if (isset($data[$name])) {
                            $num = 1;

                            while (isset($data[$name . $num])) {
                                $num++;
                            }

                            $name .= $num;
                        }

                        $counter = 0;

                        foreach ($items as $item) {
                            $id = explode('=', $item, 2);

                            if (count($id) == 2) {
                                $data[$name][$id[0]] = $id[1];
                            } else {
                                $data[$name][$counter] = $id[0];
                            }

                            $counter++;
                        }
                    }
                }
            }
        }

        return $data;
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
        $socket = socket_create(AF_INET, SOCK_STREAM, SOL_TCP);

        if (false === $socket) {
            $error = socket_strerror(socket_last_error());
            throw new MinerSocketCreateErrorException("ERR: socket create(TCP) failed " . $error);
        }

        $connection = socket_connect($socket, $address, $port);

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