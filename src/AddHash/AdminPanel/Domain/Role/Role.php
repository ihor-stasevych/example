<?php

namespace App\AddHash\AdminPanel\Domain\Role;

class Role
{
	/** @var int */
	protected $id;

	/** @var string */
	protected $name;

	/** @var bool */
	protected $locked;

	/** @var int */
	protected $weight;

	/** @var array */
	protected $permissions;

	/** @var array */
	protected $availablePermissions;

	/**
	 * @param string $name
	 * @param int $weight
	 * @param array $permissions
	 * @param bool $locked
	 */
	public function __construct(string $name, int $weight, array $permissions, $locked = false)
	{
		$this->name = $name;
		$this->weight = $weight;
		$this->permissions = $permissions;
		$this->availablePermissions = [];
		$this->locked = $locked;
	}

	/**
	 * @param string $name
	 * @param int $weight
	 * @param array $permissions
	 */
	public function update(string $name, int $weight, array $permissions)
	{
		$this->name = $name;
		$this->weight = $weight;
		$this->permissions = $permissions;
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
	 * @return array
	 */
	public function getPermissions(): array
	{
		return $this->permissions;
	}

	/**
	 * @return bool
	 */
	public function isLocked(): bool
	{
		return $this->locked;
	}

	/**
	 * @return int
	 */
	public function getWeight(): int
	{
		return $this->weight;
	}

	/**
	 * @param array $permissions
	 */
	public function setPermissions(array $permissions): void
	{
		$this->permissions = $permissions;
	}

	/**
	 * @return array
	 */
	public function getAvailablePermissions(): array
	{
		return $this->availablePermissions;
	}

	/**
	 * @param array $availablePermissions
	 */
	public function setAvailablePermissions(array $availablePermissions): void
	{
		$this->availablePermissions = $availablePermissions;
	}
}