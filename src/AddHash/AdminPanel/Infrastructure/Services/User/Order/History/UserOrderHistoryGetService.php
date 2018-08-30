<?php

namespace App\AddHash\AdminPanel\Infrastructure\Services\User\Order\History;

use App\AddHash\AdminPanel\Domain\User\User;
use App\AddHash\AdminPanel\Domain\Payment\PaymentMethod;
use App\AddHash\AdminPanel\Domain\Store\Order\Item\StoreOrderItem;
use App\AddHash\AdminPanel\Domain\Store\Order\StoreOrderRepositoryInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use App\AddHash\AdminPanel\Domain\Store\Order\Exceptions\StoreOrderNoOrderErrorException;
use App\AddHash\AdminPanel\Domain\User\Services\Order\History\UserOrderHistoryGetServiceInterface;
use App\AddHash\AdminPanel\Domain\User\Command\Miner\Order\History\UserOrderHistoryGetCommandInterface;

class UserOrderHistoryGetService implements UserOrderHistoryGetServiceInterface
{
    private $tokenStorage;

    private $orderRepository;

    public function __construct(TokenStorageInterface $tokenStorage, StoreOrderRepositoryInterface $orderRepository)
    {
        $this->tokenStorage = $tokenStorage;
        $this->orderRepository = $orderRepository;
    }

    /**
     * @param UserOrderHistoryGetCommandInterface $command
     * @return array
     * @throws StoreOrderNoOrderErrorException
     */
    public function execute(UserOrderHistoryGetCommandInterface $command): array
    {
        /** @var User $user */
        $user = $this->tokenStorage->getToken()->getUser();

        $order = $orders = $this->orderRepository->getOrderPaidByIdAndUserId(
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

        /** @var PaymentMethod $paymentMethod */
        $paymentMethod = $order->getPayment()->getPaymentMethod();
        $paymentName = (null !== $paymentMethod) ? $paymentMethod->getName() : '';

        return [
            'id'                => $order->getId(),
            'createdAt'         => $order->getCreatedAt(),
            'state'             => $order->getStateAlias(),
            'paymentMethodName' => $paymentName,
            'currency'          => $order->getPayment()->getCurrency(),
            'itemsPriceTotal'   => $order->getItemsPriceTotal(),
            'items'             => $items,
        ];
    }
}