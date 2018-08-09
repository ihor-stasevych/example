<?php

namespace App\AddHash\AdminPanel\Domain\Store\Product\Command;

interface StoreProductListCommandInterface
{
	public function getSort(): ?string;

    public function getOrder(): ?string;
}