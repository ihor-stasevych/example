<?php

namespace App\AddHash\Authentication\Infrastructure\Services\UserData;

use App\AddHash\Authentication\Domain\Model\User;
use App\AddHash\Authentication\Domain\Repository\UserRepositoryInterface;
use App\AddHash\Authentication\Domain\Command\UserData\UserEmailsGetCommandInterface;
use App\AddHash\Authentication\Domain\Services\UserData\UserEmailsGetServiceInterface;
use App\AddHash\Authentication\Domain\Exceptions\UserData\UserEmailsGetInvalidUsersIdExceptions;

class UserEmailsGetService implements UserEmailsGetServiceInterface
{
    private $userRepository;

    public function __construct(UserRepositoryInterface $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    /**
     * @param UserEmailsGetCommandInterface $command
     * @return array
     * @throws UserEmailsGetInvalidUsersIdExceptions
     */
    public function execute(UserEmailsGetCommandInterface $command): array
    {
        $usersId = $command->getUsersId();

        $users = $this->userRepository->getByIds($usersId);

        if (count($users) != count($usersId)) {
            throw new UserEmailsGetInvalidUsersIdExceptions('Invalid users id');
        }

        $emails = [];

        /** @var User $user */
        foreach ($users as $user) {
            $emails[$user->getId()] = $user->getEmail();
        }

        return $emails;
    }
}