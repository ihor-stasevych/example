<?php

namespace App\AddHash\Authentication\Infrastructure\Auth;


use App\AddHash\Authentication\Infrastructure\Repository\UserRepositoryInterface;
use Symfony\Component\Security\Core\Exception\UnsupportedUserException;
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

	}

	/**
	 * Refreshes the user.
	 * It is up to the implementation to decide if the user data should be
	 * totally reloaded (e.g. from the database), or if the UserInterface
	 * object can just be merged into some internal array of users / identity
	 * map.
	 *
	 * @return UserInterface
	 * @throws UnsupportedUserException  if the user is not supported
	 * @throws UsernameNotFoundException if the user is not found
	 */
	public function refreshUser(UserInterface $user)
	{
		// TODO: Implement refreshUser() method.
	}

	/**
	 * Whether this provider supports the given user class.
	 *
	 * @param string $class
	 * @return bool
	 */
	public function supportsClass($class)
	{
		// TODO: Implement supportsClass() method.
	}
}