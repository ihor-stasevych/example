<?php

namespace App\AddHash\AdminPanel\Domain\User\Command\Order\History;

interface UserOrderHistoryGetCommandInterface
{
    public function getOrderId(): int;
}