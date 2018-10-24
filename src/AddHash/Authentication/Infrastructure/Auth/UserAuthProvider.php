<?php

namespace App\AddHash\Authentication\Infrastructure\Auth;

use App\AddHash\Authentication\Domain\Model\User;
use Symfony\Component\HttpFoundation\RequestStack;
use App\AddHash\System\Lib\Captcha\ReCaptcha\Captcha;
use App\AddHash\System\GlobalContext\ValueObject\Email;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;
use App\AddHash\Authentication\Domain\Repository\UserRepositoryInterface;
use App\AddHash\Authentication\Domain\Exceptions\UserLogin\UserLoginEmailNotExistsException;
use App\AddHash\Authentication\Domain\Exceptions\UserLogin\UserLoginInvalidVerificationCaptchaException;

class UserAuthProvider implements UserProviderInterface
{
    private $userRepository;

    private $requestStack;

    public function __construct(UserRepositoryInterface $userRepository, RequestStack $requestStack)
    {
        $this->userRepository = $userRepository;
        $this->requestStack = $requestStack;
    }

    /**
     * @param string $username
     * @return User|null|UserInterface
     * @throws UserLoginEmailNotExistsException
     * @throws UserLoginInvalidVerificationCaptchaException
     */
    public function loadUserByUsername($username)
    {
        $this->isVerifyCaptcha();

        $user = $this->userRepository->getByEmail(
            new Email($username)
        );

        if (null === $user) {
            throw new UserLoginEmailNotExistsException('Bad credentials');
        }

        return $user;
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

    public function supportsClass($class)
    {
        return User::class == $class;
    }

    /**
     * @throws UserLoginInvalidVerificationCaptchaException
     */
    private function isVerifyCaptcha()
    {
        $json = $this->requestStack->getCurrentRequest()->getContent();
        $data = json_decode($json, true);
        $captchaResponse = '';

        if (false === empty($data['g-recaptcha-response'])) {
            $captchaResponse = $data['g-recaptcha-response'];
        }

        $isVerifyCaptcha = (new Captcha())->isVerify($captchaResponse);

        if (false === $isVerifyCaptcha) {
            throw new UserLoginInvalidVerificationCaptchaException('Invalid verification captcha');
        }
    }
}