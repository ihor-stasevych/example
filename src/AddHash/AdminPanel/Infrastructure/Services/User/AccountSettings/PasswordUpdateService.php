<?php

namespace App\AddHash\AdminPanel\Infrastructure\Services\User\AccountSettings;

use App\AddHash\Authentication\Domain\Model\User;
use Symfony\Component\Security\Core\Encoder\EncoderFactoryInterface;
use App\AddHash\Authentication\Domain\Repository\UserRepositoryInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use App\AddHash\AdminPanel\Domain\User\Command\AccountSettings\PasswordUpdateCommandInterface;
use App\AddHash\AdminPanel\Domain\User\Exceptions\AccountSettings\PasswordIsNotValidException;
use App\AddHash\AdminPanel\Domain\User\Services\AccountSettings\PasswordUpdateServiceInterface;

class PasswordUpdateService implements PasswordUpdateServiceInterface
{
    private $userRepository;

    private $encoderFactory;

    private $tokenStorage;

	public function __construct(
	    UserRepositoryInterface $userRepository,
        EncoderFactoryInterface $encoderFactory,
        TokenStorageInterface $tokenStorage
    )
	{
        $this->userRepository = $userRepository;
        $this->encoderFactory = $encoderFactory;
        $this->tokenStorage = $tokenStorage;
	}

    /**
     * @param PasswordUpdateCommandInterface $command
     * @throws PasswordIsNotValidException
     */
	public function execute(PasswordUpdateCommandInterface $command)
	{
	    /** @var User $user */
        $user = $this->tokenStorage->getToken()->getUser();

        $isValidPassword = $this->encoderFactory->getEncoder(User::class)->isPasswordValid(
            $user->getPassword(),
            $command->getCurrentPassword(),
            $user->getSalt()
        );

        if (false === $isValidPassword) {
            throw new PasswordIsNotValidException('Current password is not valid');
        }

        $encodedNewPassword = $this->encoderFactory->getEncoder(User::class)->encodePassword(
            $command->getNewPassword(),
            $user->getSalt()
        );

        $user->setPassword($encodedNewPassword);
        $this->userRepository->save($user);
	}
}