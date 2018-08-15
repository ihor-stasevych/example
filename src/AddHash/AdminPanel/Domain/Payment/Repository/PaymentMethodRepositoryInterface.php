<?php

namespace App\AddHash\AdminPanel\Domain\Payment\Repository;


interface PaymentMethodRepositoryInterface
{
	public function getByName(string $name);
}