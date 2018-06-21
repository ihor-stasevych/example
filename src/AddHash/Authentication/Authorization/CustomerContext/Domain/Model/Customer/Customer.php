<?php

namespace AddHash\Authentication\Authorization\CustomerContext\Domain\Model;

use AddHash\System\GlobalContext\Identity\CustomerId;
use Symfony\Component\Security\Core\User\UserInterface;

class Customer implements UserInterface
{

	/**
	 * @var CustomerId
	 */
	private $id;

	private $email;

	private $password;

	private $name;

	public function __construct(CustomerId $id, string $email, $password, $name)
	{
		$this->id = $id;
		$this->email = $email;
		$this->password = $password;
		$this->name = $name;

	}

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
	 * Returns the password used to authenticate the user.
	 * This should be the encoded password. On authentication, a plain-text
	 * password will be salted, encoded, and then compared to this value.
	 *
	 * @return string The password
	 */
	public function getPassword()
	{
		return $this->password;
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
	 * Returns the username used to authenticate the user.
	 *
	 * @return string The username
	 */
	public function getUsername()
	{
		return $this->name;
	}

	/**
	 * Removes sensitive data from the user.
	 * This is important if, at any given point, sensitive information like
	 * the plain-text password is stored on this object.
	 */
	public function eraseCredentials()
	{
		$this->password = null;
	}
}