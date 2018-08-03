<?php

namespace App\AddHash\AdminPanel\Domain\Store\Order;
use League\Fractal\TransformerAbstract;

class StoreOrderTransformer extends  TransformerAbstract
{
	public function transform(StoreOrder $storeOrder)
	{
		return [
			'id' => $storeOrder->getId(),
			'totalPrice' => $storeOrder->getItemsPriceTotal(),
			'items' => $storeOrder->getItems()
		];
	}
}