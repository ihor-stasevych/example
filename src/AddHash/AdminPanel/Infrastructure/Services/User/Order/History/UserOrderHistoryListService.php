<?php

namespace App\AddHash\AdminPanel\Infrastructure\Services\User\Order\History;

use App\AddHash\AdminPanel\Domain\User\User;
use App\AddHash\AdminPanel\Domain\Payment\PaymentMethod;
use App\AddHash\AdminPanel\Domain\Store\Order\StoreOrder;
use App\AddHash\AdminPanel\Domain\Store\Order\Item\StoreOrderItem;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use App\AddHash\AdminPanel\Domain\User\Services\Order\History\UserOrderHistoryListServiceInterface;

class UserOrderHistoryListService implements UserOrderHistoryListServiceInterface
{
    private $tokenStorage;

    public function __construct(TokenStorageInterface $tokenStorage)
    {
        $this->tokenStorage = $tokenStorage;
    }

    public function execute(): array
    {
        /** @var User $user */
        $user = $this->tokenStorage->getToken()->getUser();
        $result = [];
        $orders = $user->getOrder();

        if (!count($orders)) {
            return $result;
        }

        /** @var StoreOrder $order */
        foreach ($orders as $order) {
            if (null === $order->getPayment()) {
                continue;
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

            /** @var PaymentMethod $paymentMethod */
            $paymentMethod = $order->getPayment()->getPaymentMethod();

            $result[] = [
                'id'                => $order->getId(),
                'createdAt'         => $order->getCreatedAt(),
                'state'             => $order->getState(),
                'paymentMethodName' => $paymentMethod->getName(),
                'currency'          => $order->getPayment()->getCurrency(),
                'itemsPriceTotal'   => $order->getItemsPriceTotal(),
                'items'             => $items,
            ];
        }

        return $result;
    }
}