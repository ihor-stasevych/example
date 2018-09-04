<?php

namespace App\AddHash\AdminPanel\Domain\Payment\Services;

use App\AddHash\AdminPanel\Domain\Payment\Command\MakeCryptoPaymentCommandInterface;
use App\AddHash\AdminPanel\Domain\User\User;

interface MakeCryptoPaymentServiceInterface
{
	public function execute(User $user, MakeCryptoPaymentCommandInterface $command);
}