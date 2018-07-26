<?php

namespace App\AddHash\AdminPanel\Domain\Payment\Gateway;

class PaymentGateway
{
	private $id;

	private $title;

	private $alias;


	public function __construct($title, $alias)
	{
		$this->title = $title;
		$this->alias = $alias;
	}

	public function getTitle()
	{
		return $this->title;
	}

	public function getAlias()
	{
		return $this->alias;
	}
}