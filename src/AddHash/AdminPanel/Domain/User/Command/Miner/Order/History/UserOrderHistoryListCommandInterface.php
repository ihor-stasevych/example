<?php

namespace App\AddHash\AdminPanel\Domain\User\Command\Miner\Order\History;

interface UserOrderHistoryListCommandInterface
{
    public function getSort(): ?string;

    public function getOrder(): ?string;

    public function getStateFilter(): ?int;
}