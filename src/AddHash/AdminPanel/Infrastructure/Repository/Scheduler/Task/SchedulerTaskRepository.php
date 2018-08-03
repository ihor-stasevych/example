<?php

namespace App\AddHash\AdminPanel\Infrastructure\Repository\Scheduler\Task;


use App\AddHash\AdminPanel\Domain\Scheduler\Task\SchedulerTask;
use App\AddHash\AdminPanel\Domain\Scheduler\Task\SchedulerTaskRepositoryInterface;
use App\AddHash\System\GlobalContext\Repository\AbstractRepository;

class SchedulerTaskRepository extends AbstractRepository implements SchedulerTaskRepositoryInterface
{
	public function findAll(): array
	{
		return $this->entityManager->createQueryBuilder()
			->select('t')
			->from($this->getEntityName(), 't')
			->getQuery()
			->getResult();
	}

	public function find(?array $types): array
	{
		$qb = $this->entityManager->createQueryBuilder();
		$qb->select('t')
			->from($this->getEntityName(), 't');

		if (!empty($types)) {
			$qb->where($qb->expr()->in('t.type', $types));
		}

		return $qb->getQuery()
			->getResult();
	}

	/**
	 * @param string $command
	 * @return SchedulerTask|null
	 * @throws \Doctrine\ORM\NonUniqueResultException
	 */
	public function findByCommand(string $command): ?SchedulerTask
	{
		$qb = $this->entityManager->createQueryBuilder();

		$qb->select('t')
			->from($this->getEntityName(), 't')
			->where($qb->expr()->eq('t.command', $qb->expr()->literal($command)));

		return $qb->getQuery()
			->getOneOrNullResult();
	}

	/**
	 * @param int $id
	 * @return SchedulerTask|null
	 * @throws \Doctrine\ORM\ORMException
	 * @throws \Doctrine\ORM\OptimisticLockException
	 * @throws \Doctrine\ORM\TransactionRequiredException
	 */
	public function getById(int $id): ?SchedulerTask
	{
		return $this->entityManager->find($this->getEntityName(), $id);
	}

	/**
	 * @param SchedulerTask $task
	 * @return mixed|void
	 * @throws \Doctrine\ORM\ORMException
	 * @throws \Doctrine\ORM\OptimisticLockException
	 */
	public function create(SchedulerTask $task)
	{
		$this->entityManager->persist($task);
		$this->entityManager->flush();
	}

	/**
	 * @param SchedulerTask $task
	 * @param bool $merge
	 * @return mixed|void
	 * @throws \Doctrine\ORM\ORMException
	 * @throws \Doctrine\ORM\OptimisticLockException
	 */
	public function update(SchedulerTask $task, $merge = false)
	{
		if ($merge === true) {
			$this->entityManager->merge($task);
		} else {
			$this->entityManager->persist($task);
		}
		$this->entityManager->flush();
	}

	/**
	 * @param SchedulerTask $task
	 * @return mixed|void
	 * @throws \Doctrine\ORM\ORMException
	 * @throws \Doctrine\ORM\OptimisticLockException
	 */
	public function delete(SchedulerTask $task)
	{
		$this->entityManager->remove($task);
		$this->entityManager->flush();
	}

	/**
	 * @return array
	 */
	public function findAllActive(): array
	{
		$qb = $this->entityManager->createQueryBuilder();
		return $qb->select('t')
			->from(SchedulerTask::class, 't')
			->where($qb->expr()->eq('t.disabled', $qb->expr()->literal(false)))
			->andWhere($qb->expr()->orX(
				$qb->expr()->eq('t.status', SchedulerTask::STATUS_DONE),
				$qb->expr()->andX(
					$qb->expr()->eq('t.status', SchedulerTask::STATUS_FAILED),
					$qb->expr()->eq('t.repeatAfterFail', $qb->expr()->literal(true))
				)
			))
			->getQuery()
			->getResult();
	}

	/**
	 * @return string
	 */
	public function getEntityName()
	{
		return SchedulerTask::class;
	}
}