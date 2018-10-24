<?php

namespace App\AddHash\Authentication\Infrastructure\Services;

use App\AddHash\Authentication\Domain\Model\User;
use App\AddHash\System\GlobalContext\ValueObject\Email;
use App\AddHash\Authentication\Domain\Repository\UserRepositoryInterface;
use App\AddHash\Authentication\Domain\Command\UserEmailUpdateCommandInterface;
use App\AddHash\Authentication\Domain\Services\UserEmailUpdateServiceInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use App\AddHash\Authentication\Domain\Exceptions\UserRegister\UserEmailUpdateEmailExistsException;

final class UserEmailUpdateService implements UserEmailUpdateServiceInterface
{
    private $userRepository;

    private $tokenStorage;

    public function __construct(UserRepositoryInterface $userRepository, TokenStorageInterface $tokenStorage)
    {
        $this->userRepository = $userRepository;
        $this->tokenStorage = $tokenStorage;
    }

    /**
     * @param UserEmailUpdateCommandInterface $command
     * @throws UserEmailUpdateEmailExistsException
     */
    public function execute(UserEmailUpdateCommandInterface $command)
    {
        /** @var User $user */
        $user = $this->tokenStorage->getToken()->getUser();

        $newEmail = $command->getEmail();
        $currentEmail = new Email($user->getEmail());

        if (false === $currentEmail->equals($newEmail)) {

            if (null !== $this->userRepository->getByEmail($newEmail)) {
                throw new UserEmailUpdateEmailExistsException(['email' => ['This email is already taken']]);
            }

            $user->setEmail($newEmail);
            $this->userRepository->save($user);
        }
    }
}