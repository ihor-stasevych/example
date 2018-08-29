<?php

namespace App\AddHash\AdminPanel\Domain\User\Command\Miner\Order\History;

interface UserOrderHistoryGetCommandInterface
{
    public function getOrderId(): int;
}