<?php

namespace App\AddHash\AdminPanel\Application\DataFixtures;

use Doctrine\ORM\Id\AssignedGenerator;
use Doctrine\ORM\Mapping\ClassMetadata;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use App\AddHash\AdminPanel\Domain\Miners\MinerStock;
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
            $miner = new MinerStock($d['priority'], $d['ip'], $d['port'], $d['id']);

            switch($d['state']) {
                case MinerStock::STATE_AVAILABLE:
                    $miner->setAvailable();
                    break;
                case MinerStock::STATE_RESERVED:
                    $miner->reserveMiner();
                    break;
            }

            $product = $manager->getRepository(StoreProduct::class)->find($d['productId']);
            $miner->setProduct($product);

            $minerDetails = new Miner(
                $d['details']['title'],
                $d['details']['description'],
                $d['details']['hashRate'],
                $d['details']['powerRate'],
                $d['details']['powerEfficiency'],
                $d['details']['ratedVoltage'],
                $d['details']['operatingTemperature'],
                $d['details']['algorithm']
            );

            $minerDetails->setMiner($miner);

            $metadata = $manager->getClassMetadata(MinerStock::class);
            $metadata->setIdGenerator(new AssignedGenerator());
            $metadata->setIdGeneratorType(ClassMetadata::GENERATOR_TYPE_NONE);

            $manager->persist($miner);
            $manager->persist($minerDetails);
        }

        $manager->flush();

        return true;
    }

    private function getData(): array
    {
        $data = [];
        $state = 1;

        for ($i = 1; $i <= 73; $i++) {
            if ($i <= 14) {
                $state = 3;
            }

            $data[] = [
                'id'        => $i,
                'state'     => $state,
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
            ];
        }

        return $data;
    }
}