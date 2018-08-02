<?php

namespace App\AddHash\AdminPanel\Infrastructure\Services\User;

use App\AddHash\AdminPanel\Domain\User\User;
use App\AddHash\System\GlobalContext\ValueObject\Email;
use App\AddHash\AdminPanel\Domain\User\UserRepositoryInterface;
use Symfony\Component\Security\Core\Encoder\EncoderFactoryInterface;
use App\AddHash\AdminPanel\Domain\User\Command\UserRegisterCommandInterface;
use App\AddHash\AdminPanel\Domain\User\Services\UserRegisterServiceInterface;
use App\AddHash\AdminPanel\Domain\User\Exceptions\UserRegisterEmailExistException;
use App\AddHash\AdminPanel\Domain\User\Exceptions\UserRegisterUserNameExistException;

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
     * @throws UserRegisterEmailExistException
     * @throws UserRegisterUserNameExistException
     */
	public function execute(UserRegisterCommandInterface $command)
	{
		$email = new Email($command->getEmail());

        $user = $this->userRepository->getByEmailOrUserName($email, $command->getUserName());

		if (null !== $user) {
		    if ($user->getUsername() == $command->getUserName()) {
                throw new UserRegisterUserNameExistException('User name already exists');
            }

            throw new UserRegisterEmailExistException('User email already exists');
        }

		$user = new User(
			null,
			$command->getUserName(),
			$email,
			'',
			$command->getBackupEmail(),
			$command->getFirstName(),
			$command->getLastName(),
			$command->getPhone(),
			$command->getRoles()
		);

        $encodedPassword = $this->encoderFactory->getEncoder(User::class)->encodePassword(
            $command->getPassword(),
            $user->getSalt()
        );
        $user->setPassword($encodedPassword);

		$this->userRepository->create($user);

		return $user;
	}
}