<?php

namespace App\AddHash\AdminPanel\Infrastructure\Services\Scheduler\Task;

use Cron\CronExpression;
use Psr\Log\LoggerInterface;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Bundle\FrameworkBundle\Console\Application;
use App\AddHash\AdminPanel\Domain\Scheduler\Task\SchedulerTask;
use App\AddHash\AdminPanel\Domain\Scheduler\Task\SchedulerTaskRepositoryInterface;
use App\AddHash\AdminPanel\Domain\Scheduler\Task\Services\SchedulerTaskWorkerServiceInterface;
use Symfony\Component\VarDumper\VarDumper;

class SchedulerTaskWorkerService implements SchedulerTaskWorkerServiceInterface
{
    private $repository;

    private $logger;

    public function __construct(SchedulerTaskRepositoryInterface $repository, LoggerInterface $logger)
    {
        $this->repository = $repository;
        $this->logger = $logger;
    }

    /**
     * @param Application $application
     * @param OutputInterface $output
     * @throws \Exception
     */
    public function execute(Application $application, OutputInterface $output)
    {
        $tasks = $this->repository->findAllActive();
        $countTasks = count($tasks);

        for ($i = 0; $i < $countTasks; $i++) {

	        VarDumper::dump($tasks[$i]);

            $consoleCommand = $tasks[$i]->getCommand();

            if (false === $application->has($consoleCommand)) {
                $this->logger->error('Invalid scheduler command',(array) $tasks[$i]);
                continue;
            }

            if (false === $this->isTimeCronExpression($tasks[$i])) {
                continue;
            }

            $tasks[$i]->setStatus(SchedulerTask::STATUS_IN_PROGRESS);
            $this->repository->update($tasks[$i]);

            $statusTask = $application->find($consoleCommand)->run(
                new ArrayInput($tasks[$i]->getArgumentsArray()),
                $output
            );

            if ($statusTask == 1) {
                $tasks[$i]->setStatus(SchedulerTask::STATUS_DONE);
                $tasks[$i]->setLastExecution(new \DateTime());
            } else {
                $tasks[$i]->setStatus(SchedulerTask::STATUS_FAILED);
                $this->logger->error('Change status scheduler command failed',(array) $tasks[$i]);
            }

            $this->repository->update($tasks[$i]);
        }
    }

    private function isTimeCronExpression(SchedulerTask $task): bool
    {
        $cron = CronExpression::factory($task->getCronExpression());
        $nexRunDateTime = $cron->getNextRunDate($task->getLastExecution())->format('Y-m-d H:i:s');

        $currentDateTime = new \DateTime();
        $nexRunDateTime = new \DateTime($nexRunDateTime);

        return $currentDateTime > $nexRunDateTime;
    }
}