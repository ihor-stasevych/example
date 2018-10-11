<?php

namespace App\AddHash\AdminPanel\Infrastructure\Services\User;

use App\AddHash\AdminPanel\Domain\User\Command\PasswordRecovery\UserPasswordRecoveryCommandInterface;
use App\AddHash\AdminPanel\Domain\User\Password\UserPasswordRecovery;
use App\AddHash\AdminPanel\Domain\User\Password\UserPasswordRecoveryRepositoryInterface;
use App\AddHash\AdminPanel\Domain\User\Services\UserPasswordRecoveryServiceInterface;
use App\AddHash\AdminPanel\Domain\User\User;
use App\AddHash\AdminPanel\Domain\User\UserRepositoryInterface;
use Symfony\Component\Security\Core\Encoder\EncoderFactoryInterface;

class UserPasswordRecoveryService implements UserPasswordRecoveryServiceInterface
{
	private $recoveryRepository;
	private $userRepository;
	private $encoderFactory;

	public function __construct(
		UserPasswordRecoveryRepositoryInterface $recoveryRepository,
		UserRepositoryInterface $userRepository,
		EncoderFactoryInterface $encoderFactory
	)
	{
		$this->recoveryRepository = $recoveryRepository;
		$this->userRepository = $userRepository;
		$this->encoderFactory = $encoderFactory;
	}

	/**
	 * @param UserPasswordRecoveryCommandInterface $command
	 * @return bool
	 * @throws \Exception
	 */
	public function execute(UserPasswordRecoveryCommandInterface $command)
	{
		/** @var UserPasswordRecovery $passwordRecovery */
		$passwordRecovery = $this->recoveryRepository->findByHash($command->getHash());

		if (!$passwordRecovery) {
			throw new \Exception('Incorrect hash');
		}

		$dateTime = new \DateTime();
		$dateTime->setTimestamp($dateTime->getTimestamp());

		if ($passwordRecovery->getExpirationDate() < $dateTime) {
			throw new \Exception('Your hash has been expired');
		}

		/** @var User $user */
		$user = $passwordRecovery->getUser();

		if (!$user) {
			throw new \Exception('This user does not exists');
		}

		$encodedPassword = $this->encoderFactory
			->getEncoder(User::class)
			->encodePassword($command->getPassword(), $user->getSalt());

		$user->setPassword($encodedPassword);
		$this->userRepository->update();

		return true;
	}
}