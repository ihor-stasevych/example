<?php

namespace App\AddHash\AdminPanel\Infrastructure\Services\User\Order\History;

use App\AddHash\AdminPanel\Domain\User\User;
use App\AddHash\AdminPanel\Domain\Payment\PaymentMethod;
use App\AddHash\AdminPanel\Domain\Store\Order\StoreOrder;
use App\AddHash\AdminPanel\Domain\Store\Order\Item\StoreOrderItem;
use App\AddHash\AdminPanel\Domain\Store\Order\StoreOrderRepositoryInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use App\AddHash\AdminPanel\Domain\User\Services\Order\History\UserOrderHistoryListServiceInterface;

class UserOrderHistoryListService implements UserOrderHistoryListServiceInterface
{
    private $tokenStorage;

    private $orderRepository;

    public function __construct(TokenStorageInterface $tokenStorage, StoreOrderRepositoryInterface $orderRepository)
    {
        $this->tokenStorage = $tokenStorage;
        $this->orderRepository = $orderRepository;
    }

    public function execute(): array
    {
        /** @var User $user */
        $user = $this->tokenStorage->getToken()->getUser();

        $orders = $this->orderRepository->getOrdersPaidByUserId($user->getId());

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

            /** @var PaymentMethod $paymentMethod */
            $paymentMethod = $order->getPayment()->getPaymentMethod();
            $paymentName = (null !== $paymentMethod) ? $paymentMethod->getName() : '';

            $result[] = [
                'id'                => $order->getId(),
                'createdAt'         => $order->getCreatedAt(),
                'state'             => $order->getStateAlias(),
                'paymentMethodName' => $paymentName,
                'currency'          => $order->getPayment()->getCurrency(),
                'itemsPriceTotal'   => $order->getItemsPriceTotal(),
                'items'             => $items,
            ];
        }

        return $result;
    }
}