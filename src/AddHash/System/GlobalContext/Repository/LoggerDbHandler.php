<?php

namespace App\AddHash\System\GlobalContext\Repository;
use App\AddHash\AdminPanel\Domain\Logger\Model\Logger;
use Doctrine\ORM\EntityManager;

use Monolog\Handler\AbstractProcessingHandler;

class LoggerDbHandler extends AbstractProcessingHandler
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
		parent::__construct();
		$this->entityManager = $entityManager;
		$this->entityRepository = $entityManager->getRepository(Logger::class);

	}


	/**
	 *
	 * Writes the record down to the log of the implementing handler
	 *
	 * @param array $record
	 * @throws \Doctrine\ORM\ORMException
	 * @throws \Doctrine\ORM\OptimisticLockException
	 */
	protected function write(array $record)
	{
		$log = new Logger();
		$log->setMessage($record['message']);
		$log->setLevel($record['level']);
		$log->setLevelName($record['level_name']);
		$log->setExtra($record['extra']);
		$log->setContext($record['context']);

		$this->entityManager->persist($log);
		$this->entityManager->flush($log);
	}
}