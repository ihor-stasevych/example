<?php

namespace App\AddHash\AdminPanel\Infrastructure\Services\User\Order\History;

use App\AddHash\AdminPanel\Domain\Payment\Payment;
use App\AddHash\AdminPanel\Domain\Payment\PaymentMethod;
use App\AddHash\AdminPanel\Domain\Store\Order\StoreOrder;
use App\AddHash\AdminPanel\Domain\Store\Order\Item\StoreOrderItem;
use App\AddHash\AdminPanel\Domain\Store\Order\StoreOrderRepositoryInterface;
use App\AddHash\AdminPanel\Domain\User\Services\UserGetAuthenticationServiceInterface;
use App\AddHash\AdminPanel\Domain\Store\Order\Exceptions\StoreOrderNoOrderErrorException;
use App\AddHash\AdminPanel\Domain\User\Command\Order\History\UserOrderHistoryGetCommandInterface;
use App\AddHash\AdminPanel\Domain\User\Services\Order\History\UserOrderHistoryGetServiceInterface;

final class UserOrderHistoryGetService implements UserOrderHistoryGetServiceInterface
{
    private $authenticationService;

    private $orderRepository;

    public function __construct(UserGetAuthenticationServiceInterface $authenticationService, StoreOrderRepositoryInterface $orderRepository)
    {
        $this->authenticationService = $authenticationService;
        $this->orderRepository = $orderRepository;
    }

    /**
     * @param UserOrderHistoryGetCommandInterface $command
     * @return array
     * @throws StoreOrderNoOrderErrorException
     */
    public function execute(UserOrderHistoryGetCommandInterface $command): array
    {
        $user = $this->authenticationService->execute();

        $order = $orders = $this->orderRepository->getOrderByIdAndUserId(
            $command->getOrderId(),
            $user->getId()
        );

        if (null === $order) {
            throw new StoreOrderNoOrderErrorException('No order');
        }

        $items = [];

        /** @var StoreOrderItem $item */
        foreach ($order->getItems() as $item) {
            $items[] = [
                'quantity'     => $item->getQuantity(),
                'price'        => $item->getTotalPrice(),
                'totalPrice'   => $item->getTotalPrice(),
                'productTitle' => $item->getProduct()->getTitle(),
            ];
        }

        $paymentName = '';
        $currency = '';
        /** @var Payment $payment */
        $payment = $order->getPayment();

        if (null !== $payment && $order->getState() == StoreOrder::STATE_PAYED) {
            /** @var PaymentMethod $paymentMethod */
            $paymentMethod = $payment->getPaymentMethod();
            $paymentName = $paymentMethod->getName();
            $currency = $payment->getCurrency();
        }

        return [
            'id'                => $order->getId(),
            'createdAt'         => $order->getCreatedAt(),
            'state'             => $order->getStateAlias(),
            'paymentMethodName' => $paymentName,
            'currency'          => $currency,
            'itemsPriceTotal'   => $order->getItemsPriceTotal(),
            'items'             => $items,
        ];
    }
}