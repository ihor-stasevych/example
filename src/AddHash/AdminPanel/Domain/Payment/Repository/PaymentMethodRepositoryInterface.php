<?php

namespace App\AddHash\AdminPanel\Domain\Payment\Repository;

use App\AddHash\AdminPanel\Domain\Payment\PaymentMethod;

interface PaymentMethodRepositoryInterface
{
	public function getByName(string $name): ?PaymentMethod;
}