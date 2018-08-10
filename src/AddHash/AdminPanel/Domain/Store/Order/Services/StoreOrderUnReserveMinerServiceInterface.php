<?php

namespace App\AddHash\AdminPanel\Domain\Store\Order\Services;

interface StoreOrderUnReserveMinerServiceInterface
{
	public function execute(): array;
}