<?php

namespace App\AddHash\AdminPanel\Application\ConsoleCommand;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class ProcessSchedulerTask extends Command
{
	public function __construct()
	{
		parent::__construct();
	}
}