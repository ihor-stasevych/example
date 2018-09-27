<?php

namespace App\AddHash\AdminPanel\Domain\Store\Order\Command;

interface StoreOrderCreateCommandInterface
{
	public function getProducts();

	public function getItemsPriceTotal();
}