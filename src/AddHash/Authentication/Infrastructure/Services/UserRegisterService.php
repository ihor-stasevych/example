<?php

namespace App\AddHash\Authentication\Infrastructure\Services;

use App\AddHash\Authentication\Domain\Model\User;
use App\AddHash\System\Lib\Captcha\ReCaptcha\Captcha;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\Security\Core\Encoder\EncoderFactoryInterface;
use App\AddHash\Authentication\Application\Command\UserRegisterCommand;
use App\AddHash\Authentication\Domain\Repository\UserRepositoryInterface;
use App\AddHash\Authentication\Domain\Services\UserRegisterServiceInterface;
use App\AddHash\Authentication\Domain\Exceptions\UserRegister\UserRegisterUserAlreadyExistsException;
use App\AddHash\Authentication\Domain\Exceptions\UserRegister\UserRegisterInvalidVerifyCaptchaException;

final class UserRegisterService implements UserRegisterServiceInterface
{
    private const JWT_MANAGER_ALIAS = 'lexik_jwt_authentication.jwt_manager';


    private $encoderFactory;

    private $container;

    private $userRepository;

    public function __construct(
        EncoderFactoryInterface $encoderFactory,
        ContainerInterface $container,
        UserRepositoryInterface $userRepository
    )
    {
        $this->encoderFactory = $encoderFactory;
        $this->container = $container;
        $this->userRepository = $userRepository;
    }

    /**
     * @param UserRegisterCommand $command
     * @return array
     * @throws UserRegisterInvalidVerifyCaptchaException
     * @throws UserRegisterUserAlreadyExistsException
     */
    public function execute(UserRegisterCommand $command): array
    {
        $isVerifyCaptcha = (new Captcha())->isVerify($command->getCaptcha());

        if (false === $isVerifyCaptcha) {
            throw new UserRegisterInvalidVerifyCaptchaException('Invalid verify captcha');
        }

        $email = $command->getEmail();

        $user = $this->userRepository->getByEmail($email);

        if (null !== $user) {
            throw new UserRegisterUserAlreadyExistsException('User ' . $email . ' already exists');
        }

        $user = new User($email, '', $command->getRoles());

        $encodedPassword = $this->encoderFactory->getEncoder(User::class)->encodePassword(
            $command->getPassword(),
            $user->getSalt()
        );

        $user->setPassword($encodedPassword);

        $this->userRepository->save($user);

        $token = $this->createToken($user);

        return ['token' => $token];
    }

    private function createToken(User $user): string
    {
        $jwtManager = $this->container->get(static::JWT_MANAGER_ALIAS);

        return $jwtManager->create($user);
    }
}