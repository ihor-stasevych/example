<?php

namespace App\AddHash\AdminPanel\Application\DataFixtures;

use Doctrine\ORM\Id\AssignedGenerator;
use Doctrine\ORM\Mapping\ClassMetadata;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use App\AddHash\AdminPanel\Domain\Store\Product\StoreProduct;

class StoreProductFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $data = $this->getData();

        if (!$data) {
            return false;
        }

        foreach ($data as $d) {
            $storeProduct = new StoreProduct($d['title'], $d['description'], $d['techDetails'], $d['price'], $d['state'], $d['categories'], $d['vote'], $d['id']);

            $metadata = $manager->getClassMetadata(StoreProduct::class);
            $metadata->setIdGenerator(new AssignedGenerator());
            $metadata->setIdGeneratorType(ClassMetadata::GENERATOR_TYPE_NONE);

            $manager->persist($storeProduct);
        }

        $manager->flush();

        return true;
    }

    private function getData(): array
    {
        return [
            [
                'id'          => 1,
                'title'       => 'AntMiner S9 10.5 TH/S',
                'description' => '',
                'techDetails' => '',
                'price'       => 100.00,
                'state'       => 1,
                'vote'        => 0,
                'categories'  => [],
            ],
            [
                'id'          => 2,
                'title'       => 'AntMiner S9 13.5 TH/S',
                'description' => '',
                'techDetails' => '',
                'price'       => 155.00,
                'state'       => 1,
                'vote'        => 0,
                'categories'  => [],
            ],
        ];
    }
}