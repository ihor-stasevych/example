<?php

namespace App\AddHash\AdminPanel\Infrastructure\Services\Payment;

use App\AddHash\AdminPanel\Domain\Payment\Gateway\PaymentGatewayInterface;
use App\AddHash\AdminPanel\Domain\Payment\Services\GetCryptoCurrenciesServiceInterface;
use App\AddHash\AdminPanel\Domain\Payment\Command\GetCurrenciesCryptoPaymentCommandInterface;

class GetCryptoCurrenciesService implements GetCryptoCurrenciesServiceInterface
{
    private $paymentGateway;

    public function __construct(PaymentGatewayInterface $paymentGateway)
    {
        $this->paymentGateway = $paymentGateway;
    }

    public function execute(GetCurrenciesCryptoPaymentCommandInterface $command)
	{
        return $this->paymentGateway->getCurrencies($command->getOrderId());
	}
}