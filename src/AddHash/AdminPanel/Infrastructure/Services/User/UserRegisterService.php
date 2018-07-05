<?php

namespace App\AddHash\AdminPanel\Infrastructure\Services\User;


use App\AddHash\AdminPanel\Domain\User\Command\UserRegisterCommandInterface;
use App\AddHash\AdminPanel\Domain\User\Exceptions\UserRegisterException;
use App\AddHash\AdminPanel\Domain\User\Services\UserRegisterServiceInterface;
use App\AddHash\AdminPanel\Domain\User\User;
use App\AddHash\AdminPanel\Domain\User\UserRepositoryInterface;
use App\AddHash\System\GlobalContext\ValueObject\Email;
use App\AddHash\System\GlobalContext\ValueObject\Phone;
use Symfony\Component\Security\Core\Encoder\EncoderFactoryInterface;

class UserRegisterService implements UserRegisterServiceInterface
{
	private $userRepository;
	private $encoderFactory;

	public function __construct(
		UserRepositoryInterface $userRepository,
		EncoderFactoryInterface $encoderFactory
	)
	{
		$this->userRepository = $userRepository;
		$this->encoderFactory = $encoderFactory;
	}


	/**
	 * @param UserRegisterCommandInterface $command
	 * @return User
	 * @throws UserRegisterException
	 */
	public function execute(UserRegisterCommandInterface $command)
	{
		$email = new Email($command->getEmail());

		if ($this->userRepository->getByEmail($email)) {
			throw new UserRegisterException('Such user already exists');
		}

		$password = $command->getPassword();
		$encodedPassword = $this->encoderFactory->getEncoder(User::class)->encodePassword($password, '');

		$user = new User(
			null,
			$command->getUserName(),
			$email,
			$encodedPassword,
			$command->getBackupEmail(),
			$command->getFirstName(),
			$command->getLastName(),
			$command->getPhone()
		);

		$this->userRepository->create($user);

		return $user;
	}
}