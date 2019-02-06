<?php

namespace App\AddHash\MinerPanel\Application\ConsoleCommand;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use App\AddHash\MinerPanel\Domain\Miner\Services\MinerCalcHashRateAverageServiceInterface;

class MinerCalcHashRateAverageConsoleCommand extends Command
{
    private $averageService;

    public function __construct(MinerCalcHashRateAverageServiceInterface $averageService)
    {
        $this->averageService = $averageService;

        parent::__construct();
    }

    protected function configure()
    {
        $this->setName('app:calc-hash-rate-average')
            ->setDescription('Calc hash rate average')
            ->setHelp('Calc hash rate average');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $status = 1;

        try {
            $this->averageService->execute();
        } catch (\Exception $e) {
            $status = 0;
        }

        return $status;
    }
}