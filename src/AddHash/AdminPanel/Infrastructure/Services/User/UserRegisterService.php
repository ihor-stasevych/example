<?php

namespace App\AddHash\AdminPanel\Infrastructure\Services\User;


use App\AddHash\AdminPanel\Domain\User\Services\UserRegisterServiceInterface;
use App\AddHash\AdminPanel\Domain\User\User;
use App\AddHash\AdminPanel\Domain\User\UserRepositoryInterface;
use App\AddHash\System\GlobalContext\Identity\UserId;
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


	public function execute(array $data = [])
	{
		$password = $data['password'];
		$encodedPassword = $this->encoderFactory->getEncoder(User::class)->encodePassword($password, '');

		$user = new User(
			null,
			$data['userName'],
			new Email($data['email']),
			$encodedPassword,
			new Email($data['backupEmail']),
			$data['firstName'],
			$data['lastName'],
			new Phone($data['phone'])
		);

		$this->userRepository->create($user);

		return $user;
	}
}