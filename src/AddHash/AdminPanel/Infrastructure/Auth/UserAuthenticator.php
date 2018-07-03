<?php

namespace App\AddHash\AdminPanel\Infrastructure\Auth;

use App\AddHash\AdminPanel\Domain\User\UserRepositoryInterface;
use App\AddHash\System\GlobalContext\Auth\JwtAuthenticator;
use App\AddHash\System\GlobalContext\ValueObject\Email;
use Lexik\Bundle\JWTAuthenticationBundle\Encoder\LcobucciJWTEncoder as JWTEncoder;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Core\User\UserProviderInterface;

class UserAuthenticator extends JwtAuthenticator
{

	private $userRepository;

	/**
	 * @var JWTEncoder
	 */
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

		$username = $data['username'];

		$user = $this->userRepository->getByEmail(new Email($username));

		if (!$user) {
			throw new AuthenticationException('Unauthorized', 401);
		}

		return $user;
	}


	public function supports(Request $request)
	{
		return false;
	}
}