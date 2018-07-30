<?php

namespace App\AddHash\AdminPanel\Domain\Wallet;

class Wallet
{
	/**
     * @var integer
     */
	private $id = null;

	/**
     * @var string
     */
	private $name;

	public function __construct(string $name, int $id = null)
	{
		$this->setId($id);
		$this->setName($name);
	}

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    private function setId($id = null)
    {
        if (null != $id) {
            $this->id = $id;
        }
    }

    private function setName(string $name)
    {
        $this->name = $name;
    }
}