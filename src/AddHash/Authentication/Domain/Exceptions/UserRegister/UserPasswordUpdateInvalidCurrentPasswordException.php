<?php

namespace App\AddHash\Authentication\Domain\Exceptions\UserRegister;

use App\AddHash\System\GlobalContext\Exceptions\Error406Exception;

class UserPasswordUpdateInvalidCurrentPasswordException extends Error406Exception
{

}