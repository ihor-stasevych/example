<?php

namespace App\AddHash\AdminPanel\Domain\Store\Order\Command;

use App\AddHash\AdminPanel\Domain\User\User;

interface StoreOrderCreateCommandInterface
{
	public function getProducts();

	public function getItemsPriceTotal();

	public function getUser() : User;
}