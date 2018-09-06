<?php

namespace App\AddHash\AdminPanel\Domain\Miners;

class MinerPool
{
    private $id;

    private $url;

    private $user;

    private $password;

    private $minerStock;

	public function __construct(string $url, string $user, string $password = null, $id = null)
	{
	    $this->id = $id;
		$this->url = $url;
        $this->user = $user;
        $this->setPassword($password);
	}

	public function getId(): ?int
    {
        return $this->id;
    }

	public function getUrl(): string
    {
        return $this->url;
    }

    public function getUser(): string
    {
        return $this->user;
    }

    public function getPassword(): string
    {
        return $this->password;
    }

    public function setPassword(string $password = null)
    {
        $this->password = (null === $password) ? '' : $password;
    }
}