<?php

namespace App\AddHash\AdminPanel\Application\DataFixtures;

use Doctrine\ORM\Id\AssignedGenerator;
use Doctrine\ORM\Mapping\ClassMetadata;
use Doctrine\Bundle\FixturesBundle\Fixture;
use App\AddHash\AdminPanel\Domain\User\User;
use Doctrine\Common\Persistence\ObjectManager;
use App\AddHash\AdminPanel\Domain\Payment\Payment;
use App\AddHash\AdminPanel\Infrastructure\Payment\Gateway\Stripe\PaymentGatewayStripe;

class PaymentFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $data = $this->getData();

        if (!$data) {
            return false;
        }

        foreach ($data as $d) {
            $user = $manager->getRepository(User::class)->find($d['userId']);
            $payment = new Payment($d['price'], $d['currency'], $user, $d['id']);
            $payment->setPaymentGateway(new PaymentGatewayStripe($payment));

            $metadata = $manager->getClassMetadata(Payment::class);
            $metadata->setIdGenerator(new AssignedGenerator());
            $metadata->setIdGeneratorType(ClassMetadata::GENERATOR_TYPE_NONE);

            $manager->persist($payment);
        }

        $manager->flush();

        return true;
    }

    private function getData(): array
    {
        return [
            [
                'id'       => 1,
                'currency' => 'usd',
                'price'    => '100',
                'userId'   => 2,
            ],
            [
                'id'       => 2,
                'currency' => 'usd',
                'price'    => '100',
                'userId'   => 3,
            ],
            [
                'id'       => 3,
                'currency' => 'usd',
                'price'    => '100',
                'userId'   => 3,
            ],
            [
                'id'       => 4,
                'currency' => 'usd',
                'price'    => '100',
                'userId'   => 3,
            ],
            [
                'id'       => 5,
                'currency' => 'usd',
                'price'    => '100',
                'userId'   => 3,
            ],
            [
                'id'       => 6,
                'currency' => 'usd',
                'price'    => '100',
                'userId'   => 3,
            ],
        ];
    }
}