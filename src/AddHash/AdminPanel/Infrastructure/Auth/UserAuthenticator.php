<?php

namespace App\AddHash\AdminPanel\Infrastructure\Auth;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use App\AddHash\System\GlobalContext\Auth\JwtAuthenticator;
use Symfony\Component\Security\Core\User\UserProviderInterface;
use App\AddHash\AdminPanel\Domain\User\UserRepositoryInterface;
use App\AddHash\AdminPanel\Domain\Captcha\CaptchaCacheInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Lexik\Bundle\JWTAuthenticationBundle\Encoder\LcobucciJWTEncoder as JWTEncoder;
use App\AddHash\AdminPanel\Domain\Captcha\Services\ReCaptcha\ReCaptchaServiceInterface;

class UserAuthenticator extends JwtAuthenticator
{
	private $userRepository;

	private $jwtEncoder;

	private $reCaptchaService;

	private $captchaCache;

	public function __construct(
	    UserRepositoryInterface $repository,
        JWTEncoder $jwtEncoder,
        ReCaptchaServiceInterface $reCaptchaService,
        CaptchaCacheInterface $captchaCache
    )
	{
		$this->userRepository = $repository;
		$this->jwtEncoder = $jwtEncoder;
		$this->reCaptchaService = $reCaptchaService;
		$this->captchaCache = $captchaCache;
	}

    public function getCredentials(Request $request)
    {
        $isVerify = $this->reCaptchaService->execute($request->get('g-recaptcha-response'));

        if (false === $isVerify) {
            throw new AuthenticationException('Invalid verification captcha', 401);
        }

        return parent::getCredentials($request);
    }

	public function getUser($credentials, UserProviderInterface $userProvider)
	{
		try {
			$data = $this->jwtEncoder->decode($credentials);
		} catch (\Exception $e) {
			throw new AuthenticationException('Unauthorized', 401);
		}

		$username = $data['username'];

		$user = $this->userRepository->getByUserName($username);

		if (null === $user) {
			throw new AuthenticationException('Unauthorized', 401);
		}

		return $user;
	}

    public function onAuthenticationSuccess(Request $request, TokenInterface $token, $providerKey)
    {
        $this->captchaCache->clear(
            $request->getClientIp(),
            $request->headers->get('User-Agent')
        );

        return parent::onAuthenticationSuccess($request, $token, $providerKey);
    }

	public function supports(Request $request)
	{
		return $request->headers->has('Authorization');
	}
}