<?php

namespace App\AddHash\AdminPanel\Domain\Scheduler\Task;


interface SchedulerTaskRepositoryInterface
{
	/**
	 * @return SchedulerTask[]
	 */
	public function findAll(): array;

	/**
	 * @param array|null $types
	 * @return SchedulerTask[]
	 */
	public function find(?array $types): array;

	/**
	 * @param string $command
	 * @return null|SchedulerTask
	 */
	public function findByCommand(string $command): ?SchedulerTask;

	/**
	 * @param int $id
	 * @return null|SchedulerTask
	 */
	public function getById(int $id): ?SchedulerTask;

	/**
	 * @param SchedulerTask $task
	 * @return mixed
	 */
	public function create(SchedulerTask $task);

	/**
	 * @param SchedulerTask $task
	 * @param bool $merge
	 * @return mixed
	 */
	public function update(SchedulerTask $task, $merge = false);

	/**
	 * @param SchedulerTask $task
	 * @return mixed
	 */
	public function delete(SchedulerTask $task);

	/**
	 * @return SchedulerTask[]
	 */
	public function findAllActive(): array;
}