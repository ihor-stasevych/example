<?php

namespace App\AddHash\Authentication\Infrastructure;


use Symfony\Component\Security\Core\User\UserInterface;

class User extends \App\AddHash\Authentication\Domain\Model\User implements UserInterface
{

	/**
	 * Returns the roles granted to the user.
	 * <code>
	 * public function getRoles()
	 * {
	 *     return array('ROLE_USER');
	 * }
	 * </code>
	 * Alternatively, the roles might be stored on a ``roles`` property,
	 * and populated in any number of different ways when the user object
	 * is created.
	 *
	 * @return (Role|string)[] The user roles
	 */
	public function getRoles()
	{
		return ['ROLE_USER'];
	}

	/**
	 * Returns the salt that was originally used to encode the password.
	 * This can return null if the password was not encoded using a salt.
	 *
	 * @return string|null The salt
	 */
	public function getSalt()
	{
		return '';
	}

	/**
	 * Removes sensitive data from the user.
	 * This is important if, at any given point, sensitive information like
	 * the plain-text password is stored on this object.
	 */
	public function eraseCredentials()
	{
		return '';
	}
}