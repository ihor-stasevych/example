<?php

namespace App\AddHash\AdminPanel\Domain\Miners\Commands;

use App\AddHash\AdminPanel\Domain\Miners\Parsers\ParserInterface;

interface MinerCommandInterface
{
    public function setParser(ParserInterface $parser);

    public function request($cmd);

    public function getConfig();

    public function getSummary();

    public function getPools();

    public function getState();

    public function getVersion();

    public function addPool(string $url, string $user, string $password);

    public function removePool(int $id);

    public function disablePool(int $id);

    public function enablePool(int $id);
}