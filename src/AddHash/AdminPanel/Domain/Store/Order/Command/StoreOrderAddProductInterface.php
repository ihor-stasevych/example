<?php

namespace App\AddHash\AdminPanel\Domain\Store\Order\Command;

interface StoreOrderAddProductInterface
{
	public function getOrder();

	public function getProduct();
}