<?php

namespace App\AddHash\AdminPanel\Domain\Store\Category\Services;

use App\AddHash\AdminPanel\Domain\Store\Category\Command\StoreCategoryCreateCommandInterface;

interface CreateServiceInterface
{
	public function execute(StoreCategoryCreateCommandInterface $categoryCreateCommand);
}