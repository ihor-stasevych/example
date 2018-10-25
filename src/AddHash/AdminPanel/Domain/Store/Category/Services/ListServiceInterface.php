<?php

namespace App\AddHash\AdminPanel\Domain\Store\Category\Services;

interface ListServiceInterface
{
	public function execute();

	public function getOne($id);
}