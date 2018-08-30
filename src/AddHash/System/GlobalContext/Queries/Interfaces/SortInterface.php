<?php

namespace App\AddHash\System\GlobalContext\Queries\Interfaces;

interface SortInterface
{
    public function getSort(): string;

    public function getOrder(): string;
}