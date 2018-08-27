<?php

namespace App\AddHash\AdminPanel\Domain\Miners;

class MinerAllowedUrl
{
    const ENABLE = 1;

    const DISABLE = 0;


    private $id;

    private $value;

    private $status;

	public function __construct(string $value, $id = null)
	{
	    $this->id = $id;
		$this->value = $value;
		$this->status = static::ENABLE;
	}

	public function getId(): ?int
    {
        return $this->id;
    }

	public function getValue(): string
    {
        return $this->value;
    }

    public function getStatus(): int
    {
        return $this->status;
    }

    public function setValue(string $value)
    {
        $this->value = $value;
    }

    public function setStatusEnabled()
    {
        $this->status = static::ENABLE;
    }

    public function setStatusDisabled()
    {
        $this->status = static::DISABLE;
    }
}