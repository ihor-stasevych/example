<?php

namespace App\AddHash\Authentication\Infrastructure\Auth;

use Symfony\Component\HttpFoundation\Request;
use App\AddHash\System\GlobalContext\ValueObject\Email;
use App\AddHash\System\GlobalContext\Auth\JwtAuthenticator;
use Symfony\Component\Security\Core\User\UserProviderInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use App\AddHash\Authentication\Domain\Repository\UserRepositoryInterface;
use Lexik\Bundle\JWTAuthenticationBundle\Encoder\LcobucciJWTEncoder as JWTEncoder;

class UserAuthenticator extends JwtAuthenticator
{
    private $userRepository;

    private $jwtEncoder;

    public function __construct(UserRepositoryInterface $repository, JWTEncoder $jwtEncoder)
    {
        $this->userRepository = $repository;
        $this->jwtEncoder = $jwtEncoder;
    }

    public function getUser($credentials, UserProviderInterface $userProvider)
    {
        try {
            $data = $this->jwtEncoder->decode($credentials);
        } catch (\Exception $e) {
            throw new AuthenticationException('Unauthorized', 401);
        }

        $user = $this->userRepository->getByEmail(
            new Email($data['username'])
        );

        if (null === $user) {
            throw new AuthenticationException('Unauthorized', 401);
        }

        return $user;
    }

    public function supports(Request $request)
    {
        return $request->headers->has('Authorization');
    }
}