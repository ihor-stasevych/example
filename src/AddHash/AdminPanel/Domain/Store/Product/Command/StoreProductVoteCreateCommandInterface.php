<?php

namespace App\AddHash\AdminPanel\Domain\Store\Product\Command;

interface StoreProductVoteCreateCommandInterface
{
	public function getProductId();

	public function getValue();
}