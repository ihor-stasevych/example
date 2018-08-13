<?php

namespace App\AddHash\AdminPanel\Application\DataFixtures;

use Doctrine\ORM\Id\AssignedGenerator;
use Doctrine\ORM\Mapping\ClassMetadata;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use App\AddHash\AdminPanel\Domain\Miners\Miner;
use App\AddHash\AdminPanel\Domain\Store\Product\StoreProduct;

class MinerFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $data = $this->getData();

        if (!$data) {
            return false;
        }

        foreach ($data as $d) {
            $miner = new Miner($d['priority'], $d['ip'], $d['port'], $d['id']);

            switch($d['state']) {
                case Miner::STATE_AVAILABLE:
                    $miner->setAvailable();
                    break;
                case Miner::STATE_RESERVED:
                    $miner->reserveMiner();
                    break;
            }

            $product = $manager->getRepository(StoreProduct::class)->find($d['productId']);
            $miner->setProduct($product);

            $metadata = $manager->getClassMetadata(Miner::class);
            $metadata->setIdGenerator(new AssignedGenerator());
            $metadata->setIdGeneratorType(ClassMetadata::GENERATOR_TYPE_NONE);

            $manager->persist($miner);
        }

        $manager->flush();

        return true;
    }

    private function getData(): array
    {
        return [
            [
                'id'        => 1,
                'state'     => 3,
                'priority'  => 2,
                'ip'        => '10.0.10.6',
                'port'      => 4028,
                'productId' => 1,
                'details'   => [
                    'title'                => 'AntMiner_Details S9 10 TH/S',
                    'description'          => '',
                    'hashRate'             => '10 TH/S',
                    'powerRate'            => '1300W',
                    'powerEfficiency'      => '',
                    'ratedVoltage'         => '11.60 ~13.00V',
                    'operatingTemperature' => '0 - 40 °C',
                    'algorithm'            => 'SHA256',
                ],
            ],
        ];
    }
}