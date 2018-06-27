<?php
namespace App\AddHash\AdminPanel\Customer\CustomerContext\Infrastructure\Repository;

use Doctrine\ORM\EntityManager;

class CustomerRepository
{
	protected $entityManager;

	public function __construct(EntityManager $entityManager)
	{
		$this->entityManager = $entityManager;
	}
}