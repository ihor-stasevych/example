<?php

namespace App\AddHash\AdminPanel\Infrastructure\Transformers\Store;

use App\AddHash\AdminPanel\Domain\Store\Product\StoreProduct;
use League\Fractal\TransformerAbstract;

class StoreProductTransform extends TransformerAbstract
{
	public function transform(StoreProduct $product)
	{
		return [
			'id' => $product->getId(),
			'categories' => $product->getCategories()
		];
	}
}