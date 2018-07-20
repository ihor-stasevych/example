<?php

namespace App\AddHash\System\GlobalContext\Repository;

use Doctrine\ORM\EntityManager;

abstract class AbstractRepository
{
	/**
	 * @var EntityManager
	 */
	protected $entityManager;

	protected $entityRepository;

	/**
	 * AbstractRepository constructor.
	 *
	 * @param EntityManager $entityManager
	 */
	public function __construct(EntityManager $entityManager)
	{
		$this->entityManager = $entityManager;
		$this->entityRepository = $entityManager->getRepository($this->getEntityName());

	}

	/**
	 * @return string
	 */
	abstract protected function getEntityName();
}