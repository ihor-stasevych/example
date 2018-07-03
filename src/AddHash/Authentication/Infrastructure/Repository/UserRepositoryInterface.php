<?php

namespace App\AddHash\Authentication\Infrastructure\Repository;

use App\AddHash\Authentication\Infrastructure\User;
use App\AddHash\System\GlobalContext\ValueObject\Email;

interface UserRepositoryInterface
{
	public function getByEmail(Email $email): ?User;
}