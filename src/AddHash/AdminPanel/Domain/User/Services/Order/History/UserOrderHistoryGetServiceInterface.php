<?php

namespace App\AddHash\AdminPanel\Domain\User\Services\Order\History;

use App\AddHash\AdminPanel\Domain\User\Command\Miner\Order\History\UserOrderHistoryGetCommandInterface;

interface UserOrderHistoryGetServiceInterface
{
    public function execute(UserOrderHistoryGetCommandInterface $command): array;
}