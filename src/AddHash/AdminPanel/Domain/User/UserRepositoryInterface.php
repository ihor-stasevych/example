<?php

namespace App\AddHash\AdminPanel\Domain\User;

use App\AddHash\System\GlobalContext\Identity\UserId;
use App\AddHash\System\GlobalContext\ValueObject\Email;

interface UserRepositoryInterface
{
	/***
	 * @param UserId $id
	 * @return User|null
	 */
	public function getById(UserId $id): ?User;

	/**
	 * @param Email $email
	 * @return User|null
	 */
	public function getByEmail(Email $email): ?User;

	/**
	 * @param string $username
	 * @return User|null
	 */
	public function getByUserName(string $username): ?User;

	/**
	 * @param User $user
	 * @return mixed
	 */
	public function create(User $user);

	/**
	 * @param User $user
	 * @return mixed
	 */
	public function update(User $user);
}