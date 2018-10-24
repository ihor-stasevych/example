<?php

namespace App\AddHash\AdminPanel\Domain\Store\Order\Command;

interface StoreOrderAddProductCommandInterface
{
	public function getOrder();

	public function getProduct();

	public function isInteger($value): bool;
}