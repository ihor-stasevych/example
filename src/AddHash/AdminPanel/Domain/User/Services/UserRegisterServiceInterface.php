<?php

namespace App\AddHash\AdminPanel\Domain\User\Services;


interface UserRegisterServiceInterface
{
	public function execute(array $data = []);
}