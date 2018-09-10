<?php

namespace App\AddHash\AdminPanel\Infrastructure\Miners\SSH2;

use App\AddHash\AdminPanel\Domain\Miners\SSH2\Exceptions\SSH2AuthFailException;

class SSH2AuthPubKey
{
    /**
     * SSH2AuthPubKey constructor.
     * @param $connection
     * @param string $user
     * @param string $publicKey
     * @param string $privateKey
     * @param string $passPhrase
     * @throws SSH2AuthFailException
     */
    public function __construct(
        $connection,
        string $user,
        string $publicKey,
        string $privateKey,
        string $passPhrase = ''
    ) {
        $auth = @ssh2_auth_pubkey_file(
            $connection,
            $user,
            $publicKey,
            $privateKey,
            $passPhrase
        );

        if (false === $auth) {
            throw new SSH2AuthFailException('Auth fail');
        }
    }
}