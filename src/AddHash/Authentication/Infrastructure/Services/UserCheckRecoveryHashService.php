<?php

namespace App\AddHash\Authentication\Infrastructure\Services;

use App\AddHash\Authentication\Domain\Model\UserPasswordRecovery;
use App\AddHash\Authentication\Domain\Services\UserCheckRecoveryHashServiceInterface;
use App\AddHash\Authentication\Domain\Command\UserPasswordRecoveryHashCommandInterface;
use App\AddHash\Authentication\Domain\Repository\UserPasswordRecoveryRepositoryInterface;
use App\AddHash\Authentication\Domain\Exceptions\UserResetPassword\UserResetPasswordInvalidTokenException;

final class UserCheckRecoveryHashService implements UserCheckRecoveryHashServiceInterface
{
    private $userPasswordRecoveryRepository;

    public function __construct(UserPasswordRecoveryRepositoryInterface $userPasswordRecoveryRepository)
    {
        $this->userPasswordRecoveryRepository = $userPasswordRecoveryRepository;
    }

    /**
     * @param UserPasswordRecoveryHashCommandInterface $command
     * @return UserPasswordRecovery
     * @throws UserResetPasswordInvalidTokenException
     */
    public function execute(UserPasswordRecoveryHashCommandInterface $command): UserPasswordRecovery
    {
        $userPasswordRecovery = $this->userPasswordRecoveryRepository->findByHash($command->getHash());

        if (null === $userPasswordRecovery) {
            throw new UserResetPasswordInvalidTokenException('Token is expired or not valid');
        }

        $dateTime = new \DateTime();
        $dateTime->setTimestamp($dateTime->getTimestamp());

        if ($userPasswordRecovery->getExpirationDate() < $dateTime) {
            throw new UserResetPasswordInvalidTokenException('Your token has been expired');
        }

        return $userPasswordRecovery;
    }
}