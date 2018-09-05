<?php

namespace App\AddHash\AdminPanel\Domain\Payment\Command;


interface MakeCryptoPaymentCommandInterface
{
	public function getCurrency();
}