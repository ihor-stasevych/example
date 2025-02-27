<?php

namespace App\AddHash\AdminPanel\Domain\Miners;

class MinerAllowedUrl
{
    const ENABLE = 1;

    const DISABLE = 0;


    private $id;

    private $value;

    private $algorithm;

    private $status;

	public function __construct(
	    string $value,
        MinerAlgorithm $algorithm,
        $id = null
    )
	{
	    $this->id = $id;
		$this->value = $value;
		$this->algorithm = $algorithm;
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

    public function setValue(string $value): void
    {
        $this->value = $value;
    }

    public function setStatusEnabled(): void
    {
        $this->status = static::ENABLE;
    }

    public function setStatusDisabled(): void
    {
        $this->status = static::DISABLE;
    }
}