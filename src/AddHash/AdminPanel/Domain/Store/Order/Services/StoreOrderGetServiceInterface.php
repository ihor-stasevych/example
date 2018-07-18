<?php

namespace App\AddHash\AdminPanel\Domain\Store\Order\Services;


use App\AddHash\AdminPanel\Domain\User\User;

interface StoreOrderGetServiceInterface
{
	public function execute(User $user);
}