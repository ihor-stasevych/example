<?php

namespace App\AddHash\AdminPanel\Domain\Scheduler\Task\Services;

use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Bundle\FrameworkBundle\Console\Application;

interface SchedulerTaskWorkerServiceInterface
{
    public function execute(Application $application, OutputInterface $output);
}