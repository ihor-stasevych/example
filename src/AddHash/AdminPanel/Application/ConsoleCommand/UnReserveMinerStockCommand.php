<?php

namespace App\AddHash\AdminPanel\Application\ConsoleCommand;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use App\AddHash\AdminPanel\Domain\User\Miner\UnReserveMinerStockServiceInterface;

class UnReserveMinerStockCommand extends Command
{
    private $service;

    public function __construct(UnReserveMinerStockServiceInterface $service)
    {
        $this->service = $service;

        parent::__construct();
    }

    protected function configure()
    {
        $this->setName('app:un-reserve-miner-stock')
            ->setDescription('un reserve miner stock')
            ->setHelp('Un reserve miner stock after the end of the rental period');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        try {
            $this->service->execute();
        } catch (\Exception $e) {}

        return 1;
    }
}