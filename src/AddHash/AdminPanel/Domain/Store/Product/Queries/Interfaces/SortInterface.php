<?php

namespace App\AddHash\AdminPanel\Domain\Store\Product\Queries\Interfaces;

interface SortInterface
{
    public function getSort(): string;

    public function getOrder(): string;
}