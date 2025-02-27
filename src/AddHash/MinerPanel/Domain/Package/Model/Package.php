<?php

namespace App\AddHash\MinerPanel\Domain\Package\Model;

class Package
{
    private $id;

    private $title;

    private $description;

    private $price;

    private $options;

    private $isDefaultPackage;

    public function __construct(int $id, string $title, $price, string $description)
    {
        $this->id = $id;
        $this->title = $title;
        $this->price = $price;
		$this->description = $description;
		$this->isDefaultPackage = false;
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getTitle()
    {
    	return $this->title;
    }

    public function getDescription()
    {
    	return $this->description;
    }

    public function getOptions()
    {
		return $this->options;
    }
}