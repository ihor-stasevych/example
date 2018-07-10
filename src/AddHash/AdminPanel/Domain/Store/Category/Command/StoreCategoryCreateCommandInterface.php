<?php

namespace App\AddHash\AdminPanel\Domain\Store\Category\Command;


interface StoreCategoryCreateCommandInterface
{
	public function getTitle();

	public function getPosition();
}