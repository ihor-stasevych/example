<?php

namespace App\AddHash\AdminPanel\Application\ConsoleCommand;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Bundle\FrameworkBundle\Console\Application;
use App\AddHash\AdminPanel\Domain\Scheduler\Task\Services\SchedulerTaskWorkerServiceInterface;

class ProcessSchedulerTask extends Command
{
    private $service;

	public function __construct(SchedulerTaskWorkerServiceInterface $service)
	{
	    $this->service = $service;

        parent::__construct();
	}

    protected function configure()
    {
        $this->setName('app:process-scheduler-task')
            ->setDescription('Start scheduler')
            ->setHelp('Start scheduler');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        /** @var Application $application */
        $application = $this->getApplication();
        $this->service->execute($application, $output);
    }
}