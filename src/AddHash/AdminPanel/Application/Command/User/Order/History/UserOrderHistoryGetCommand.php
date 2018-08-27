<?php

namespace App\AddHash\AdminPanel\Application\Command\User\Order\History;

use App\AddHash\AdminPanel\Domain\User\Command\Miner\Order\History\UserOrderHistoryGetCommandInterface;

class UserOrderHistoryGetCommand implements UserOrderHistoryGetCommandInterface
{
    private $orderId;

    public function __construct($orderId)
    {
        $this->orderId = $orderId;
    }

    public function getOrderId(): int
    {
        return $this->orderId;
    }
}