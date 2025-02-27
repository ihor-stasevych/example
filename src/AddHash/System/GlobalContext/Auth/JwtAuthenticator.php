<?php

namespace App\AddHash\System\GlobalContext\Auth;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;
use Symfony\Component\Security\Guard\AbstractGuardAuthenticator;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Lexik\Bundle\JWTAuthenticationBundle\TokenExtractor\AuthorizationHeaderTokenExtractor;

/**
 * Class JwtAuthenticator
 * @package Orange\Platform\SecurityBundle\Auth
 */
abstract class JwtAuthenticator extends AbstractGuardAuthenticator
{
	/**
	 * @param Request $request
	 * @param AuthenticationException $authException
	 * @return JsonResponse
	 */
	public function start(Request $request, AuthenticationException $authException = null)
	{
		return new JsonResponse(['error' => 'Auth header required'], 401);
	}

	/**
	 * @param Request $request
	 * @return null|string
	 */
	public function getCredentials(Request $request)
	{
		if (!$request->headers->has('Authorization')) {
            throw new AuthenticationException('Auth header required', 401);
		}

		$extractor = new AuthorizationHeaderTokenExtractor(
			'Bearer',
			'Authorization'
		);

		$token = $extractor->extract($request);

		if (!$token) {
            throw new AuthenticationException('Invalid token', 401);
		}

		return $token;
	}

	/**
	 * @param mixed $credentials
	 * @param UserProviderInterface $userProvider
	 * @return null
	 */
	abstract public function getUser($credentials, UserProviderInterface $userProvider);

	/**
	 * @param mixed $credentials
	 * @param UserInterface $user
	 * @return bool
	 */
	public function checkCredentials($credentials, UserInterface $user)
	{
		return true;
	}

    /**
     * @param Request $request
     * @param AuthenticationException $exception
     * @return void
     */
	public function onAuthenticationFailure(Request $request, AuthenticationException $exception)
	{
        throw new AuthenticationException($exception->getMessage(), 401);
	}

	/**
	 * @param Request $request
	 * @param TokenInterface $token
	 * @param string $providerKey
	 * @return array
	 */
	public function onAuthenticationSuccess(Request $request, TokenInterface $token, $providerKey)
	{
		return null;
	}

	/**
	 * @return bool
	 */
	public function supportsRememberMe()
	{
		return false;
	}
}