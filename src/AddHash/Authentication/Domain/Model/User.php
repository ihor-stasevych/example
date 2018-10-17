<?php

namespace App\AddHash\Authentication\Domain\Model;

use App\AddHash\System\Lib\Uuid;
use App\AddHash\System\GlobalContext\ValueObject\Email;
use Symfony\Component\Security\Core\User\UserInterface;

class User implements UserInterface
{
    const ROLE_USER = 'ROLE_USER';

	const ROLES = [
	    self::ROLE_USER,
    ];

	private const SALT = '';


	private $id;

    private $email;

	private $password;

    private $roles;

	private $token;

	private $createdAt;

	private $updatedAt;

    public function __construct(Email $email, string $password, array $roles, $id = null)
    {
        $this->id = $id;
        $this->email = $email;
        $this->password = $password;
        $this->roles = $roles;

        $this->token = Uuid::generate();
        $this->createdAt = new \DateTime();
        $this->updatedAt = new \DateTime();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function getUserName(): string
    {
        return $this->email;
    }

    public function getPassword(): string
    {
        return $this->password;
    }

    public function getRoles(): array
    {
        return $this->roles;
    }

    public function getSalt(): string
    {
        return self::SALT;
    }

    public function setEmail(Email $email)
    {
        $this->email = $email;
    }

    public function setPassword(string $password)
    {
        $this->password = $password;
    }

    public function eraseCredentials() {}
}