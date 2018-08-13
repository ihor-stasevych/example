<?php

namespace App\AddHash\AdminPanel\Application\DataFixtures;

use Doctrine\ORM\Id\AssignedGenerator;
use Doctrine\ORM\Mapping\ClassMetadata;
use Doctrine\Bundle\FixturesBundle\Fixture;
use App\AddHash\AdminPanel\Domain\User\User;
use Doctrine\Common\Persistence\ObjectManager;
use App\AddHash\AdminPanel\Domain\Payment\Payment;
use App\AddHash\AdminPanel\Domain\Store\Order\StoreOrder;
use App\AddHash\AdminPanel\Domain\Store\Product\StoreProduct;
use App\AddHash\AdminPanel\Domain\Store\Order\Item\StoreOrderItem;

class StoreOrderFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $data = $this->getData();

        if (!$data) {
            return false;
        }

        foreach ($data as $d) {
            $user = $manager->getRepository(User::class)->find($d['userId']);
            $payment = $manager->getRepository(Payment::class)->find($d['paymentId']);
            $storeOrder = new StoreOrder($user, $d['id']);
            $storeOrder->setPayment($payment);

            if ($d['items']) {
                foreach ($d['items'] as $item) {
                    $product = $manager->getRepository(StoreProduct::class)->find($item['productId']);
                    $storeOrder->addItem(new StoreOrderItem($storeOrder, $product, $item['quantity']));
                }
            }

            $metadata = $manager->getClassMetadata(StoreProduct::class);
            $metadata->setIdGenerator(new AssignedGenerator());
            $metadata->setIdGeneratorType(ClassMetadata::GENERATOR_TYPE_NONE);

            $manager->persist($storeOrder);
        }

        $manager->flush();

        return true;
    }

    private function getData(): array
    {
        return [
            [
                'id'        => 2,
                'paymentId' => 1,
                'userId'    => 2,
                'items'     => [
                    [
                        'productId' => 1,
                        'quantity'  => 1,
                    ],
                ],
            ],
            [
                'id'        => 3,
                'paymentId' => 2,
                'userId'    => 3,
                'items'     => [
                    [
                        'productId' => 1,
                        'quantity'  => 1,
                    ],
                ],
            ],
        ];
    }
}