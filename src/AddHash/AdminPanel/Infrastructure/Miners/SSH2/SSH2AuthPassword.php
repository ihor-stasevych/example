<?php

namespace App\AddHash\AdminPanel\Infrastructure\Miners\SSH2;

use App\AddHash\AdminPanel\Domain\Miners\SSH2\Exceptions\SSH2AuthFailException;

class SSH2AuthPassword
{
    /**
     * SSH2AuthPassword constructor.
     * @param $connection
     * @param string $user
     * @param string $password
     * @throws SSH2AuthFailException
     */
    public function __construct($connection, string $user, string $password) {
        $auth = @ssh2_auth_password(
            $connection,
            $user,
            $password
        );

        if (false === $auth) {
            throw new SSH2AuthFailException('Auth fail');
        }
    }
}