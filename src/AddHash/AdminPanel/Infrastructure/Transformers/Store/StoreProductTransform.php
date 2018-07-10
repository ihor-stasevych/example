<?php
/**
 * Created by PhpStorm.
 * User: igorstasevich
 * Date: 10.07.2018
 * Time: 17:44
 */

namespace App\AddHash\AdminPanel\Infrastructure\Transformers\Store;

use App\AddHash\AdminPanel\Domain\Store\Product\StoreProduct;
use League\Fractal\TransformerAbstract;

class StoreProductTransform extends TransformerAbstract
{
	public function transform(StoreProduct $product)
	{
		return [
			'id' => $product->getId(),
			'cat' => $product->getCategories()
		];
	}
}