<?php

namespace App\AddHash\Authentication\Infrastructure\Services;

use App\AddHash\Authentication\Domain\Model\User;
use Symfony\Component\Security\Core\Encoder\EncoderFactoryInterface;
use App\AddHash\Authentication\Domain\Repository\UserRepositoryInterface;
use App\AddHash\Authentication\Application\Command\UserPasswordRecoveryHashCommand;
use App\AddHash\Authentication\Domain\Command\UserPasswordRecoveryCommandInterface;
use App\AddHash\Authentication\Domain\Services\UserRecoveryPasswordServiceInterface;
use App\AddHash\Authentication\Domain\Repository\UserPasswordRecoveryRepositoryInterface;
use App\AddHash\Authentication\Domain\Exceptions\UserResetPassword\UserResetPasswordInvalidTokenException;
use App\AddHash\Authentication\Domain\Exceptions\UserResetPassword\UserResetPasswordUserNotExistsException;

final class UserRecoveryPasswordService implements UserRecoveryPasswordServiceInterface
{
    private $userPasswordRecoveryRepository;

    private $userRepository;

    private $encoderFactory;

    private $userCheckRecoveryHashService;

    public function __construct(
        UserPasswordRecoveryRepositoryInterface $userPasswordRecoveryRepository,
        UserRepositoryInterface $userRepository,
        EncoderFactoryInterface $encoderFactory,
        UserCheckRecoveryHashService $userCheckRecoveryHashService
    )
    {
        $this->userPasswordRecoveryRepository = $userPasswordRecoveryRepository;
        $this->userRepository = $userRepository;
        $this->encoderFactory = $encoderFactory;
        $this->userCheckRecoveryHashService = $userCheckRecoveryHashService;
    }

    /**
     * @param UserPasswordRecoveryCommandInterface $command
     * @throws UserResetPasswordInvalidTokenException
     * @throws UserResetPasswordUserNotExistsException
     */
    public function execute(UserPasswordRecoveryCommandInterface $command): void
    {
        $commandCheckHash = new UserPasswordRecoveryHashCommand($command->getHash());

        $userPasswordRecovery = $this->userCheckRecoveryHashService->execute($commandCheckHash);

        $user = $userPasswordRecovery->getUser();

        if (null === $user) {
            throw new UserResetPasswordUserNotExistsException('This user does not exists');
        }

        $encodedPassword = $this->encoderFactory
            ->getEncoder(User::class)
            ->encodePassword(
                $command->getPassword(),
                $user->getSalt()
            );

        $user->setPassword($encodedPassword);
        $this->userRepository->save($user);

        $this->userPasswordRecoveryRepository->remove($userPasswordRecovery);
    }
}