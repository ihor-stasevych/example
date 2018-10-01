<?php

namespace App\AddHash\AdminPanel\Domain\Store\Product\ListParam;

use App\AddHash\System\GlobalContext\Common\SortList;
use App\AddHash\System\GlobalContext\Queries\Interfaces\SortInterface;

class Sort extends SortList implements SortInterface
{
    const SORTED_FIELDS = [
        'createdAt', 'price', 'avail'
    ];

    protected function getDefaultOrder()
    {
        return static::ORDER_DESC;
    }

    protected function getDefaultSort()
    {
        return 'createdAt';
    }

    protected function getOrdersList(): array
    {
        return static::ORDERS;
    }

    protected function getSortList(): array
    {
        return static::SORTED_FIELDS;
    }
}