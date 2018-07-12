<?php

namespace App\AddHash\AdminPanel\Domain\Store\Product\Services;

use Symfony\Component\HttpFoundation\Request;

interface StoreProductListServiceInterface
{
	public function execute();
	public function search(Request $request);
}