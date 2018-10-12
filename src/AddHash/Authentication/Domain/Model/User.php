<?php

namespace App\AddHash\Authentication\Domain\Model;

use App\AddHash\System\Lib\Uuid;
use Doctrine\Common\Collections\ArrayCollection;
use App\AddHash\AdminPanel\Domain\User\UserWallet;
use App\AddHash\System\GlobalContext\ValueObject\Email;
use App\AddHash\System\GlobalContext\ValueObject\Phone;
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

	private $backupEmail = '';

	private $firstName = '';

	private $lastName = '';

	private $phone = '';

	private $token;

	private $createdAt;

	private $updatedAt;

	private $order;

	private $wallet;

	private $vote;

	private $orderMain;

    public function __construct(Email $email, string $password, array $roles)
    {
        $this->email = $email;
        $this->password = $password;
        $this->roles = $roles;

        $this->token = Uuid::generate();
        $this->createdAt = new \DateTime();
        $this->updatedAt = new \DateTime();

        $this->order = new ArrayCollection();
        $this->wallet = new ArrayCollection();
        $this->vote = new ArrayCollection();
        $this->orderMain = new ArrayCollection();
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

    public function getBackupEmail(): string
    {
        return $this->backupEmail;
    }

    public function getFirstName(): string
    {
        return $this->firstName;
    }

    public function getLastName(): string
    {
        return $this->lastName;
    }

    public function getPhoneNumber(): ?string
    {
        return $this->phone;
    }

    public function getUserWallets()
    {
        return $this->wallet;
    }

    public function getOrderMiner()
    {
        return $this->orderMain;
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

    public function setBackupEmail(Email $email)
    {
        $this->backupEmail = $email;
    }

    public function setFirstName(string $firstName)
    {
        $this->firstName = $firstName;
    }

    public function setLastName(string $lastName)
    {
        $this->lastName = $lastName;
    }

    public function setPhoneNumber(Phone $phoneNumber)
    {
        $this->phone = $phoneNumber;
    }

    public function setUserWallet(UserWallet $wallet)
    {
        $this->wallet = $wallet;
    }

    public function eraseCredentials() {}
}