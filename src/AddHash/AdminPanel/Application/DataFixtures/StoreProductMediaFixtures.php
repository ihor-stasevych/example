<?php

namespace App\AddHash\AdminPanel\Application\DataFixtures;

use Doctrine\ORM\Id\AssignedGenerator;
use Doctrine\ORM\Mapping\ClassMetadata;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use App\AddHash\AdminPanel\Domain\Store\Product\StoreProduct;
use App\AddHash\AdminPanel\Domain\Store\Product\Media\StoreProductMedia;

class StoreProductMediaFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $data = $this->getData();

        if (!$data) {
            return false;
        }

        foreach ($data as $d) {
            $storeProduct = new StoreProductMedia($d['id']);
            $storeProduct->setType($d['type']);
            $storeProduct->setSrc($d['src']);
            $storeProduct->setProduct($manager->getRepository(StoreProduct::class)->find($d['productId']));

            $metadata = $manager->getClassMetadata(StoreProductMedia::class);
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
                'id'        => 1,
                'type'      => 1,
                'src'       => 'https://i1.rozetka.ua/goods/4921002/40164624_images_4921002896.jpg',
                'productId' => 1,
            ],
            [
                'id'        => 2,
                'type'      => 1,
                'src'       => 'https://sc01.alicdn.com/kf/UTB8pkqgaf2JXKJkSanr7613lVXad/Newest-Version-100-Antminer-S9-10-5TH.png_350x350.png',
                'productId' => 1,
            ],
        ];
    }
}