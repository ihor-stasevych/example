<?php

namespace App\AddHash\AdminPanel\Infrastructure\Services\User;

use App\AddHash\AdminPanel\Domain\User\User;
use Symfony\Component\HttpFoundation\RequestStack;
use App\AddHash\System\GlobalContext\ValueObject\Email;
use Symfony\Component\DependencyInjection\ContainerInterface;
use App\AddHash\AdminPanel\Domain\User\UserRepositoryInterface;
use App\AddHash\AdminPanel\Domain\Captcha\CaptchaCacheInterface;
use Symfony\Component\Security\Core\Encoder\EncoderFactoryInterface;
use App\AddHash\AdminPanel\Domain\User\Command\UserRegisterCommandInterface;
use App\AddHash\AdminPanel\Domain\User\Services\UserRegisterServiceInterface;
use App\AddHash\AdminPanel\Domain\User\Exceptions\UserRegisterEmailExistException;
use App\AddHash\AdminPanel\Domain\User\Exceptions\UserRegisterUserNameExistException;
use App\AddHash\AdminPanel\Domain\Captcha\Services\ReCaptcha\ReCaptchaServiceInterface;
use App\AddHash\AdminPanel\Domain\User\Exceptions\UserRegisterInvalidVerificationCaptchaException;

class UserRegisterService implements UserRegisterServiceInterface
{
	private $userRepository;

	private $encoderFactory;

    private $captchaCache;

    private $reCaptchaService;

    private $container;

    private $requestStack;

	public function __construct(
		UserRepositoryInterface $userRepository,
		EncoderFactoryInterface $encoderFactory,
        CaptchaCacheInterface $captchaCache,
        ReCaptchaServiceInterface $reCaptchaService,
        ContainerInterface $container,
        RequestStack $requestStack
	)
	{
		$this->userRepository = $userRepository;
		$this->encoderFactory = $encoderFactory;
        $this->captchaCache = $captchaCache;
        $this->reCaptchaService = $reCaptchaService;
        $this->container = $container;
        $this->requestStack = $requestStack;
	}

    /**
     * @param UserRegisterCommandInterface $command
     * @return array
     * @throws UserRegisterEmailExistException
     * @throws UserRegisterInvalidVerificationCaptchaException
     * @throws UserRegisterUserNameExistException
     */
	public function execute(UserRegisterCommandInterface $command): array
	{
        $isVerify = $this->reCaptchaService->execute($command->getResponseCaptcha());

        if (false === $isVerify) {
            throw new UserRegisterInvalidVerificationCaptchaException('Invalid verification captcha');
        }

		$email = new Email($command->getEmail());

        $user = $this->userRepository->getByEmailOrUserName($email, $command->getUserName());

		if (null !== $user) {
		    if ($user->getUsername() == $command->getUserName()) {
                throw new UserRegisterUserNameExistException('User name already exists');
            }

            throw new UserRegisterEmailExistException('User email already exists');
        }

		$user = new User(
			null,
			$command->getUserName(),
			$email,
			'',
			$command->getBackupEmail(),
			$command->getRoles()
		);

        $encodedPassword = $this->encoderFactory->getEncoder(User::class)->encodePassword(
            $command->getPassword(),
            $user->getSalt()
        );

        $user->setPassword($encodedPassword);

		$this->userRepository->create($user);

        $request = $this->requestStack->getCurrentRequest();
        $ip = $request->getClientIp();
        $userAgent = $request->headers->get('User-Agent');

        $this->captchaCache->clear($ip, $userAgent);

        return $this->getToken($user);
	}

	private function getToken(User $user): array
    {
        $jwtManager = $this->container->get('lexik_jwt_authentication.jwt_manager');

        return ['token' => $jwtManager->create($user)];
    }
}