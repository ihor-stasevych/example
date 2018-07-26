<?php

namespace App\AddHash\AdminPanel\Infrastructure\Services\User\AccountSettings;

use App\AddHash\AdminPanel\Domain\User\User;
use App\AddHash\System\GlobalContext\Identity\UserId;
use App\AddHash\AdminPanel\Domain\User\UserRepositoryInterface;
use Symfony\Component\Security\Core\Encoder\EncoderFactoryInterface;
use App\AddHash\AdminPanel\Domain\User\Command\AccountSettings\PasswordUpdateCommandInterface;
use App\AddHash\AdminPanel\Domain\User\Exceptions\AccountSettings\PasswordIsNotValidException;
use App\AddHash\AdminPanel\Domain\User\Services\AccountSettings\PasswordUpdateServiceInterface;

class PasswordUpdateService implements PasswordUpdateServiceInterface
{
    private $userRepository;

    private $encoderFactory;

	public function __construct(UserRepositoryInterface $userRepository, EncoderFactoryInterface $encoderFactory)
	{
        $this->userRepository = $userRepository;
        $this->encoderFactory = $encoderFactory;
	}

    /**
     * @param PasswordUpdateCommandInterface $command
     * @throws PasswordIsNotValidException
     */
	public function execute(PasswordUpdateCommandInterface $command)
	{
        $userId = new UserId($command->getUser()->getId());
        $user = $this->userRepository->getById($userId);

        $isValidPassword = $this->encoderFactory->getEncoder(User::class)->isPasswordValid(
            $user->getPassword(),
            $command->getCurrentPassword(),
            $command->getUser()->getSalt()
        );

        if (false === $isValidPassword) {
            throw new PasswordIsNotValidException('Current password is not valid');
        }

        $encodedNewPassword = $this->encoderFactory->getEncoder(User::class)->encodePassword(
            $command->getNewPassword(),
            $command->getUser()->getSalt()
        );

        $user->setPassword($encodedNewPassword);
        $this->userRepository->update();
	}
}