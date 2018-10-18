<?php

namespace App\AddHash\Authentication\Infrastructure\Services;

use App\AddHash\Authentication\Domain\Model\User;
use Symfony\Component\Security\Core\Encoder\EncoderFactoryInterface;
use App\AddHash\Authentication\Domain\Repository\UserRepositoryInterface;
use App\AddHash\Authentication\Domain\Command\UserPasswordUpdateCommandInterface;
use App\AddHash\Authentication\Domain\Services\UserPasswordUpdateServiceInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use App\AddHash\Authentication\Domain\Exceptions\UserRegister\UserPasswordUpdateInvalidCurrentPasswordException;

final class UserPasswordUpdateService implements UserPasswordUpdateServiceInterface
{
    private $userRepository;

    private $tokenStorage;

    private $encoderFactory;

    public function __construct(
        UserRepositoryInterface $userRepository,
        TokenStorageInterface $tokenStorage,
        EncoderFactoryInterface $encoderFactory
    )
    {
        $this->userRepository = $userRepository;
        $this->tokenStorage = $tokenStorage;
        $this->encoderFactory = $encoderFactory;
    }

    /**
     * @param UserPasswordUpdateCommandInterface $command
     * @throws UserPasswordUpdateInvalidCurrentPasswordException
     */
    public function execute(UserPasswordUpdateCommandInterface $command)
    {
        /** @var User $user */
        $user = $this->tokenStorage->getToken()->getUser();

        $isValidPassword = $this->encoderFactory->getEncoder(User::class)->isPasswordValid(
            $user->getPassword(),
            $command->getCurrentPassword(),
            $user->getSalt()
        );

        if (false === $isValidPassword) {
            throw new UserPasswordUpdateInvalidCurrentPasswordException('Invalid current password');
        }

        $encodedNewPassword = $this->encoderFactory->getEncoder(User::class)->encodePassword(
            $command->getNewPassword(),
            $user->getSalt()
        );

        $user->setPassword($encodedNewPassword);
        $this->userRepository->save($user);
    }
}