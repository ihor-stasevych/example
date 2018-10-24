<?php

namespace App\AddHash\AdminPanel\Application\Command\User\Order\History;

use App\AddHash\AdminPanel\Domain\User\Command\Miner\Order\History\UserOrderHistoryListCommandInterface;

final class UserOrderHistoryListCommand implements UserOrderHistoryListCommandInterface
{
    private $sort;

    private $order;

    private $statusFilter;

    public function __construct($sort = null, $order = null, $statusFilter = null)
    {
        $this->sort = $sort;
        $this->order = $order;
        $this->statusFilter = $statusFilter;
    }

    public function getSort(): ?string
    {
        return $this->sort;
    }

    public function getOrder(): ?string
    {
        return $this->order;
    }

    public function getStateFilter(): ?int
    {
        return $this->statusFilter;
    }
}