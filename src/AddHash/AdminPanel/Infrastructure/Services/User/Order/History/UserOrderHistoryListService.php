<?php

namespace App\AddHash\AdminPanel\Infrastructure\Services\User\Order\History;

use App\AddHash\AdminPanel\Domain\Payment\Payment;
use App\AddHash\AdminPanel\Domain\Payment\PaymentMethod;
use App\AddHash\AdminPanel\Domain\Store\Order\StoreOrder;
use App\AddHash\AdminPanel\Domain\Store\Order\Item\StoreOrderItem;
use App\AddHash\AdminPanel\Domain\User\Order\History\ListParam\Sort;
use App\AddHash\AdminPanel\Domain\Store\Order\StoreOrderRepositoryInterface;
use App\AddHash\AdminPanel\Domain\User\Services\UserGetAuthenticationServiceInterface;
use App\AddHash\AdminPanel\Domain\User\Services\Order\History\UserOrderHistoryListServiceInterface;
use App\AddHash\AdminPanel\Domain\User\Command\Miner\Order\History\UserOrderHistoryListCommandInterface;

final class UserOrderHistoryListService implements UserOrderHistoryListServiceInterface
{
    private $authenticationService;

    private $orderRepository;

    public function __construct(UserGetAuthenticationServiceInterface $authenticationService, StoreOrderRepositoryInterface $orderRepository)
    {
        $this->authenticationService = $authenticationService;
        $this->orderRepository = $orderRepository;
    }

    public function execute(UserOrderHistoryListCommandInterface $command): array
    {
        $state = $command->getStateFilter();

        if (null !== $state && false === array_search($state, StoreOrder::STATES)) {
            $state = null;
        }

        $sort = new Sort(
            $command->getSort(),
            $command->getOrder()
        );

        $user = $this->authenticationService->execute();

        $orders = $this->orderRepository->getOrdersByUserId($user->getId(), $sort, $state);

        $result = [];

        if (empty($orders)) {
            return $result;
        }

        /** @var StoreOrder $order */
        foreach ($orders as $order) {
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

            $result[] = [
                'id'                => $order->getId(),
                'createdAt'         => $order->getCreatedAt(),
                'state'             => $order->getStateAlias(),
                'paymentMethodName' => $paymentName,
                'currency'          => $currency,
                'itemsPriceTotal'   => $order->getItemsPriceTotal(),
                'items'             => $items,
            ];
        }

        return $result;
    }
}