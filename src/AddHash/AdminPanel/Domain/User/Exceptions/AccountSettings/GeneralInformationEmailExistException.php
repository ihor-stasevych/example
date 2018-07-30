<?php

namespace App\AddHash\AdminPanel\Domain\User\Exceptions\AccountSettings;

class GeneralInformationEmailExistException extends \Exception
{
	protected $code = 400;
}