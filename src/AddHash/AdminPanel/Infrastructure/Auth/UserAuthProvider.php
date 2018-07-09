<?php

namespace App\AddHash\AdminPanel\Infrastructure\Auth;

use App\AddHash\AdminPanel\Domain\User\User;
use App\AddHash\AdminPanel\Domain\User\UserRepositoryInterface;
use App\AddHash\System\GlobalContext\ValueObject\Email;
use Symfony\Component\Security\Core\Exception\UsernameNotFoundException;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;

class UserAuthProvider implements UserProviderInterface
{
	private $userRepository;

	public function __construct(UserRepositoryInterface $userRepository)
	{
		$this->userRepository = $userRepository;
	}

	/**
	 * Loads the user for the given username.
	 * This method must throw UsernameNotFoundException if the user is not
	 * found.
	 *
	 * @param string $username The username
	 * @return UserInterface
	 * @throws UsernameNotFoundException if the user is not found
	 */
	public function loadUserByUsername($username)
	{
		$email = new Email($username);
		return $this->userRepository->getByEmail($email);

	}

	/**
	 * {@inheritdoc}
	 */
	public function refreshUser(UserInterface $user)
	{
		$this->loadUserByUsername($user->getUsername());
		return $user;
	}

	/**
	 * Whether this provider supports the given user class.
	 *
	 * @param string $class
	 * @return bool
	 */
	public function supportsClass($class)
	{
		return User::class == $class;
	}
}