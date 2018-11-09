<?php

namespace App\AddHash\AdminPanel\Domain\Miners;

class MinerStockConfig
{
    private $id;

    private $dir;

    private $fileName;

	public function __construct(string $dir, string $fileName, int $id = null)
	{
	    $this->id = $id;
		$this->dir = $dir;
		$this->fileName = $fileName;
	}

	public function getId(): ?int
    {
        return $this->id;
    }

	public function getDir(): string
    {
        return $this->dir;
    }

    public function getFileName(): string
    {
        return $this->fileName;
    }

    public function getPath(): string
    {
        return $this->dir . $this->fileName;
    }
}