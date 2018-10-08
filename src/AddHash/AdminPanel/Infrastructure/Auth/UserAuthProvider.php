<?php

namespace App\AddHash\AdminPanel\Infrastructure\Auth;

use App\AddHash\AdminPanel\Domain\User\User;
use Symfony\Component\HttpFoundation\RequestStack;
use App\AddHash\System\GlobalContext\ValueObject\Email;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;
use App\AddHash\AdminPanel\Domain\User\UserRepositoryInterface;
use Symfony\Component\Security\Core\Exception\UsernameNotFoundException;
use App\AddHash\AdminPanel\Domain\Captcha\Services\ReCaptcha\ReCaptchaServiceInterface;

class UserAuthProvider implements UserProviderInterface
{
	private $userRepository;

	private $requestStack;

    private $reCaptchaService;

	public function __construct(
	    UserRepositoryInterface $userRepository,
        RequestStack $requestStack,
        ReCaptchaServiceInterface $reCaptchaService
    )
	{
		$this->userRepository = $userRepository;
		$this->requestStack = $requestStack;
        $this->reCaptchaService = $reCaptchaService;
	}

    /**
     * Loads the user for the given username.
     * This method must throw UsernameNotFoundException if the user is not
     * found.
     *
     * @param string $username The username
     * @return UserInterface
     * @throws \Exception
     */
	public function loadUserByUsername($username)
	{
	    $this->isVerifyCaptcha();

		$email = new Email($username);

		return $this->userRepository->getByEmail($email);
	}

    /**
     * @param UserInterface $user
     * @return UserInterface
     * @throws \Exception
     */
	public function refreshUser(UserInterface $user)
	{
		$this->loadUserByUsername($user->getUsername());

		return $user;
	}

	/**
	 * Whether this provider supports the given user class.
	 *
	 * @param string $class
	 * @return bool
	 */
	public function supportsClass($class)
	{
		return User::class == $class;
	}

    /**
     * @throws \Exception
     */
	private function isVerifyCaptcha()
    {
        $json = $this->requestStack->getCurrentRequest()->getContent();

        $data = json_decode($json, true);
        $captchaResponse = (false === empty($data['g-recaptcha-response'])) ? $data['g-recaptcha-response'] : '';

        $isVerify = $this->reCaptchaService->execute($captchaResponse);

        if (false === $isVerify) {
            throw new \Exception('Invalid verification captcha');
        }
    }
}