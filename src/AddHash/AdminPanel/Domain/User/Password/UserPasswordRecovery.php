<?php

namespace App\AddHash\AdminPanel\Domain\User\Password;

use App\AddHash\Authentication\Domain\Model\User;
use App\AddHash\System\Lib\Uuid;

class UserPasswordRecovery
{
	const DURATION_TIME = 7200;

	private $id;

	private $user;

	private $hash;

	private $requestedDate;

	private $expirationDate;

	public function __construct(User $user)
	{
		$this->user = $user;
		$dataTime = new \DateTime();
		$this->expirationDate = $dataTime->setTimestamp($dataTime->getTimestamp() + self::DURATION_TIME);
		$this->requestedDate = new \DateTime();
		$this->hash = Uuid::generate();
	}

	public function getId(): ?int
	{
		return $this->id;
	}

	public function getHash()
	{
		return $this->hash;
	}

	public function getRequestedDate()
	{
		return $this->requestedDate;
	}

	public function getExpirationDate()
	{
		return $this->expirationDate;
	}

	public function setUser(User $user)
	{
		$this->user = $user;
	}

	public function getUser()
	{
		return $this->user;
	}
}