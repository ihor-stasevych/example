<?php

namespace App\AddHash\System\GlobalContext\Identity;

use App\AddHash\System\Lib\Uuid;

class UserId
{
	/** @var string */
	protected $id;

	/**
	 * CustomerId constructor.
	 *
	 * @param string|null $id
	 */
	public function __construct(string $id = null)
	{
		$this->setId($id);
	}

	/**
	 * @return string
	 */
	public function getId()
	{
		return $this->id;
	}

	/**
	 * @param string|null $id
	 */
	private function setId(string $id = null): void
	{
		if ($id === null) {
			$id = Uuid::generate();
		}

		$this->id = $id;
	}


	/**
	 * @return string
	 */
	public function __toString()
	{
		return $this->getId();
	}
}