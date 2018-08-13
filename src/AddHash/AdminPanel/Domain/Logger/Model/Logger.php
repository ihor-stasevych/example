<?php

namespace App\AddHash\AdminPanel\Domain\Logger\Model;


class Logger
{
	private $id;

	private $message;

	private $context;

	private $level;

	private $levelName;

	private $extra;

	private $createdAt;

	public function __construct()
	{
		$this->createdAt = new \DateTime();
	}

	public function setMessage(string $message)
	{
		$this->message = $message;
	}

	public function setLevel($level)
	{
		$this->level = $level;
	}

	public function setLevelName($levelName)
	{
		$this->levelName = $levelName;
	}

	public function setExtra($extra)
	{
		$this->extra = $extra;
	}

	public function setContext($context)
	{
		$this->context = $context;
	}

}