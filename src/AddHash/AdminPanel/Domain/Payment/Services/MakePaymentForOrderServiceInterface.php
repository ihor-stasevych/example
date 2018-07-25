<?php

namespace App\AddHash\AdminPanel\Domain\Payment\Services;


interface MakePaymentForOrderServiceInterface
{
	public function execute($token, $amount, $user);
}