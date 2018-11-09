<?php

namespace App\AddHash\AdminPanel\Domain\User\Notification;

use App\AddHash\AdminPanel\Domain\User\User;

class UserNotification
{
	const STATUS_NEW = 1;
	const STATUS_SEEN = 2;

	private $statusAliases = [
		self::STATUS_NEW => 'new',
		self::STATUS_SEEN => 'seen'
	];

	/** @var int */
	private $id;

	/** @var string */
	private $title;

	/** @var User */
	private $user;

	/** @var string */
	private $message;

	private $status;

	/** @var \DateTime */
	private $created;

	public function __construct(
		User $user,
		string $title,
		string $message
	) {
		$this->user = $user;
		$this->title = $title;
		$this->message = $message;
		$this->status = self::STATUS_NEW;
		$this->created = new \DateTime();
	}

	/**
	 * @return string
	 */
	public function getTitle()
	{
		return $this->title;
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
	 * @return string
	 */
	public function getStatus()
	{
		return $this->statusAliases[$this->status];
	}

	/**
	 * @return \DateTime
	 */
	public function getCreated(): \DateTime
	{
		return $this->created;
	}

	/**
	 * @return User
	 */
	public function ensureUser()
	{
		return $this->user;
	}

	public function updateStatus(int $status)
	{
		if ($status === self::STATUS_NEW) {
			$this->created = new \DateTime();
		}

		$this->status = $status;
	}
}