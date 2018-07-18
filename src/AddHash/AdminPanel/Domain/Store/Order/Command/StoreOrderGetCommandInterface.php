<?php

namespace App\AddHash\AdminPanel\Domain\Store\Order\Command;

use App\AddHash\AdminPanel\Domain\User\User;

interface StoreOrderGetCommandInterface
{
	public function getUser() : User;
}