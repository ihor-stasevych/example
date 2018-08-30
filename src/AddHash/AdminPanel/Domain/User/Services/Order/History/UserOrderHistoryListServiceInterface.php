<?php

namespace App\AddHash\AdminPanel\Domain\User\Services\Order\History;

use App\AddHash\AdminPanel\Domain\User\Command\Miner\Order\History\UserOrderHistoryListCommandInterface;

interface UserOrderHistoryListServiceInterface
{
    public function execute(UserOrderHistoryListCommandInterface $command): array;
}