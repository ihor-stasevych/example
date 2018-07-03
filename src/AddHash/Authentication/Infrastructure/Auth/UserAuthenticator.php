<?php

namespace App\AddHash\Authentication\Infrastructure\Auth;

use App\AddHash\System\GlobalContext\Auth\JwtAuthenticator;
use App\AddHash\Authentication\Infrastructure\Repository\UserRepositoryInterface;
use App\AddHash\System\GlobalContext\ValueObject\Email;
use Lexik\Bundle\JWTAuthenticationBundle\Encoder\LcobucciJWTEncoder as JWTEncoder;
use Lexik\Bundle\JWTAuthenticationBundle\Encoder\DefaultEncoder;
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

		var_dump($data);die;

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