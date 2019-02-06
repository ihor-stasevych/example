<?php

namespace App\AddHash\MinerPanel\Application\ConsoleCommand;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use App\AddHash\MinerPanel\Domain\Miner\MinerPool\Services\MinerPoolStatusUpdateServiceInterface;

class MinerPoolStatusUpdateConsoleCommand extends Command
{
    private $updateService;

    public function __construct(MinerPoolStatusUpdateServiceInterface $updateService)
    {
        $this->updateService = $updateService;

        parent::__construct();
    }

    protected function configure()
    {
        $this->setName('app:miner-pool-status-update')
            ->setDescription('Miner pool status update')
            ->setHelp('Miner pool status update');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $status = 1;

        try {
            $this->updateService->execute();
        } catch (\Exception $e) {
            $status = 0;
        }

        return $status;
    }
}