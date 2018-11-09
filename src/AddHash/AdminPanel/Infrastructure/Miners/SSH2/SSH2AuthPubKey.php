<?php

namespace App\AddHash\AdminPanel\Infrastructure\Miners\SSH2;

use App\AddHash\AdminPanel\Domain\Miners\SSH2\Exceptions\SSH2AuthFailException;

class SSH2AuthPubKey
{
    private const DEFAULT_USER = 'root';

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
        string $publicKey,
        string $privateKey,
        string $user = self::DEFAULT_USER,
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