<?php

namespace App\AddHash\AdminPanel\Infrastructure\Transformers\Store\Product;

use App\AddHash\AdminPanel\Domain\Miners\Miner;
use App\AddHash\AdminPanel\Domain\Store\Product\StoreProduct;

class StoreProductGetTransform
{
	public function transform(StoreProduct $product)
	{
	    /** @var Miner $miner */
	    $miner = $product->getMiner();

		return [
			'id'                      => $product->getId(),
            'title'                   => $product->getTitle(),
            'description'             => $product->getDescription(),
            'price'                   => $product->getPrice(),
            'techDetails'             => $product->getTechDetails(),
            'categories'              => $product->getCategories(),
            'media'                   => $product->getMedia(),
            'state'                   => $product->getState(),
            'vote'                    => $product->getVote(),
            'availableMinersQuantity' => $product->getAvailableMinersQuantity(),
            'reservedMinersQuantity'  => $product->getReservedMinersQuantity(),
            'miner'                   => [
                'id'                   => $miner->getId(),
                'title'                => $miner->getTitle(),
                'hashRate'             => $miner->getHashRate(),
                'powerRate'            => $miner->getPowerRate(),
                'powerEfficiency'      => $miner->getPowerEfficiency(),
                'ratedVoltage'         => $miner->getRatedVoltage(),
                'operatingTemperature' => $miner->getOperatingTemperature(),
                'algorithm'            => $miner->getAlgorithm()->getValue(),
            ],

		];
	}
}