<?php

namespace App\AddHash\AdminPanel\Domain\Store\Product\Command;

interface StoreProductCreateCommandInterface
{
	public function getTitle();

	public function getDescription();

	public function getTechDetails();

	public function getCategories();

	public function getMedia();

	public function getPrice();

	public function getState();
}