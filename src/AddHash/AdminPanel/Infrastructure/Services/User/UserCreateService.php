<?php

namespace App\AddHash\AdminPanel\Infrastructure\Services\User;

use App\AddHash\AdminPanel\Domain\User\User;
use App\AddHash\System\Lib\Captcha\ReCaptcha\Captcha;
use App\AddHash\AdminPanel\Domain\User\UserRepositoryInterface;
use App\AddHash\AdminPanel\Domain\User\Command\UserCreateCommandInterface;
use App\AddHash\AdminPanel\Domain\User\Services\UserCreateServiceInterface;
use App\AddHash\AdminPanel\Domain\AdapterOpenHost\AuthenticationAdapterInterface;
use App\AddHash\AdminPanel\Domain\User\Exceptions\UserCreateInvalidVerifyCaptchaException;

final class UserCreateService implements UserCreateServiceInterface
{
    private $authenticationAdapter;

    private $userRepository;

    public function __construct(
        AuthenticationAdapterInterface $authenticationAdapter,
        UserRepositoryInterface $userRepository
    )
    {
        $this->authenticationAdapter = $authenticationAdapter;
        $this->userRepository = $userRepository;
    }

    /**
     * @param UserCreateCommandInterface $command
     * @return array
     * @throws UserCreateInvalidVerifyCaptchaException
     */
    public function execute(UserCreateCommandInterface $command): array
    {
        $isVerifyCaptcha = (new Captcha())->isVerify($command->getCaptcha());

        if (false === $isVerifyCaptcha) {
            throw new UserCreateInvalidVerifyCaptchaException('Invalid verify captcha');
        }

        $data = $this->authenticationAdapter->register(
            $command->getEmail(),
            $command->getPassword(),
            ['ROLE_USER']
        );

        $user = new User($data['id']);

        $this->userRepository->create($user);

        return ['token' => $data['token']];
    }
}