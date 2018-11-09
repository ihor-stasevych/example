<?php

namespace App\AddHash\AdminPanel\Infrastructure\Transformers\Store\Product;

use App\AddHash\AdminPanel\Domain\Store\Product\StoreProduct;

class StoreProductListTransform
{
	public function transform(StoreProduct $product)
	{
		return [
			'id'                      => $product->getId(),
            'title'                   => $product->getTitle(),
            'price'                   => $product->getPrice(),
            'media'                   => $product->getMedia(),
            'state'                   => $product->getState(),
            'availableMinersQuantity' => $product->getAvailableMinersQuantity(),
		];
	}
}