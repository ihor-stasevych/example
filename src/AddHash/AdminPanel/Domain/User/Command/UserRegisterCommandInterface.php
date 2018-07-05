<?php

namespace App\AddHash\AdminPanel\Domain\User\Command;

use App\AddHash\System\GlobalContext\ValueObject\Email;
use App\AddHash\System\GlobalContext\ValueObject\Phone;

interface UserRegisterCommandInterface
{
	public function getEmail(): Email;
	public function getUserName(): string;
	public function getPassword(): string;
	public function getFirstName(): string;
	public function getLastName(): string;
	public function getBackupEmail(): Email;
	public function getPhone(): Phone;
}