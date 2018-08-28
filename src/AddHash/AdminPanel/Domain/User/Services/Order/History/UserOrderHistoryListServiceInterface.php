<?php

namespace App\AddHash\AdminPanel\Domain\User\Services\Order\History;

interface UserOrderHistoryListServiceInterface
{
    public function execute(): array;
}