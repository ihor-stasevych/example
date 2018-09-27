<?php

namespace App\AddHash\AdminPanel\Domain\User\Notification;

use App\AddHash\AdminPanel\Domain\User\User;

class UserNotification
{
	const STATUS_NEW = 1;
	const STATUS_SEEN = 2;

	/** @var int */
	private $id;

	/** @var User */
	private $user;

	/** @var string */
	private $message;

	private $status;

	/** @var \DateTime */
	private $created;

	public function __construct(
		User $user,
		string $message
	) {
		$this->user = $user;
		$this->message = $message;
		$this->status = self::STATUS_NEW;
		$this->created = new \DateTime();
	}

	/**
	 * @return int
	 */
	public function getId(): int
	{
		return $this->id;
	}

	/**
	 * @return string
	 */
	public function getMessage(): string
	{
		return $this->message;
	}

	/**
	 * @param string $message
	 */
	public function setMessage(string $message)
	{
		$this->message = $message;
	}

	/**
	 * @return \DateTime
	 */
	public function getCreated(): \DateTime
	{
		return $this->created;
	}

	public function updateStatus(int $status)
	{
		if ($status === self::STATUS_NEW) {
			$this->created = new \DateTime();
		}

		$this->status = $status;
	}
}