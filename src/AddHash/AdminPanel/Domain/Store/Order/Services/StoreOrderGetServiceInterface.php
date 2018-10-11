<?php

namespace App\AddHash\AdminPanel\Domain\Store\Order\Services;

use App\AddHash\Authentication\Domain\Model\User;

interface StoreOrderGetServiceInterface
{
	public function execute(User $user);
}