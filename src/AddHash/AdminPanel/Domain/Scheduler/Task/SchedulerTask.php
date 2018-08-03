<?php

namespace App\AddHash\AdminPanel\Domain\Scheduler\Task;


class SchedulerTask
{
	const STATUS_IN_PROGRESS = 1;
	const STATUS_DONE = 2;
	const STATUS_FAILED = 3;

	const TYPE_SYSTEM = 1;
	const TYPE_USER = 2;

	/** @var int */
	private $id;

	/** @var string */
	private $name;

	/** @var string */
	private $command;

	/** @var string|null */
	private $arguments;

	/** @var string */
	private $cronExpression;

	/** @var  \DateTime|null */
	private $lastExecution;

	/** @var int */
	private $status;

	/** @var int */
	private $type;

	/** @var bool */
	private $disabled;

	/** @var bool */
	private $repeatAfterFail;

	public function __construct(
		$name,
		$command,
		$arguments,
		$cronExpression,
		$disabled,
		$type,
		bool $repeatAfterFail = true
	)
	{
		$this->name = $name;
		$this->command = $command;
		$this->arguments = $arguments;
		$this->cronExpression = $cronExpression;
		$this->disabled = $disabled;
		$this->type = $type;
		$this->repeatAfterFail = $repeatAfterFail;
		$this->status = self::STATUS_DONE;
	}

	public function __toString()
	{
		return implode(' ', [
			$this->getCommand(),
			$this->getArguments()
		]);
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
	public function getName(): string
	{
		return $this->name;
	}

	/**
	 * @return string
	 */
	public function getCommand(): string
	{
		return $this->command;
	}

	/**
	 * @return null|string
	 */
	public function getArguments(): ?string
	{
		return $this->arguments;
	}

	/**
	 * @return string
	 */
	public function getCronExpression(): string
	{
		return $this->cronExpression;
	}

	/**
	 * @return \DateTime|null
	 */
	public function getLastExecution(): ?\DateTime
	{
		return $this->lastExecution;
	}

	/**
	 * @return bool
	 */
	public function isDisabled(): bool
	{
		return $this->disabled;
	}

	/**
	 * @return int
	 */
	public function getStatus()
	{
		return $this->status;
	}

	/**
	 * @return int
	 */
	public function getType(): int
	{
		return $this->type;
	}

	/**
	 * @return bool
	 */
	public function isRepeatAfterFail(): bool
	{
		return $this->repeatAfterFail;
	}


	/**
	 * @param string $name
	 */
	public function setName(string $name)
	{
		$this->name = $name;
	}

	/**
	 * @param string $command
	 */
	public function setCommand(string $command)
	{
		$this->command = $command;
	}

	/**
	 * @param null|string $arguments
	 */
	public function setArguments($arguments)
	{
		$this->arguments = $arguments;
	}

	public function getArgumentsArray(): array
	{
		if (!$this->arguments) {
			return [];
		}

		$result = [];
		$arguments = explode(' ', $this->arguments);

		foreach ($arguments as $argument) {
			list($name, $value) = explode('=', $argument);
			$result[$name] = $value;
		}

		return $result;
	}

	/**
	 * @param string $cronExpression
	 */
	public function setCronExpression(string $cronExpression)
	{
		$this->cronExpression = $cronExpression;
	}

	/**
	 * @param \DateTime|null $lastExecution
	 */
	public function setLastExecution($lastExecution)
	{
		$this->lastExecution = $lastExecution;
	}

	/**
	 * @param bool $disabled
	 */
	public function setDisabled(bool $disabled)
	{
		$this->disabled = $disabled;
	}

	/**
	 * @param int $status
	 */
	public function setStatus(int $status)
	{
		$this->status = $status;
	}

	/**
	 * @param bool $repeatAfterFail
	 */
	public function setRepeatAfterFail(bool $repeatAfterFail): void
	{
		$this->repeatAfterFail = $repeatAfterFail;
	}
}