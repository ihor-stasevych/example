<?php

namespace App\AddHash\AdminPanel\Domain\User\Command\AccountSettings;

use App\AddHash\AdminPanel\Domain\User\User;
use App\AddHash\System\GlobalContext\ValueObject\Email;
use App\AddHash\System\GlobalContext\ValueObject\Phone;

interface GeneralInformationUpdateCommandInterface
{
	public function getEmail(): Email;

    public function getBackupEmail(): Email;

    public function getFirstName(): string;

    public function getLastName(): string;

    public function getPhoneNumber(): Phone;

    public function isMonthlyNewsletter(): bool;

    public function getUser(): User;
}