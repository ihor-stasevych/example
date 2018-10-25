<?php

namespace App\AddHash\Authentication\Domain\Exceptions\UserRegister;

use App\AddHash\System\GlobalContext\Exceptions\Error406Exception;

class UserRegisterUserAlreadyExistsException extends Error406Exception
{

}