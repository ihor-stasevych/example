<?php

namespace App\AddHash\AdminPanel\Domain\Payment\Repository;


interface PaymentGatewayRepositoryInterface
{
	public function getByName(string $name);
}