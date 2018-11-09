<?php

namespace App\AddHash\AdminPanel\Domain\Miners;

class MinerStockPool
{
    private $id;

    private $userName;

    private $url;

    private $password;

    private $minerStock;

	public function __construct(MinerStock $minerStock, string $userName, string $url, string $password = '', $id = null)
	{
	    $this->id = $id;
	    $this->minerStock = $minerStock;
		$this->userName = $userName;
		$this->url = $url;
		$this->password = $password;
	}

	public function getId(): ?int
    {
        return $this->id;
    }

	public function getUserName(): string
    {
        return $this->userName;
    }

    public function getUrl(): string
    {
        return $this->url;
    }

    public function getPassword(): string
    {
        return $this->password;
    }
}