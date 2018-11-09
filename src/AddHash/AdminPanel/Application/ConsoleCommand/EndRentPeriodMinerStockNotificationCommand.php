<?php

namespace App\AddHash\AdminPanel\Application\ConsoleCommand;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use App\AddHash\AdminPanel\Domain\User\Miner\EndRentPeriodMinerStockNotificationServiceInterface;

class EndRentPeriodMinerStockNotificationCommand extends Command
{
    private $service;

    public function __construct(EndRentPeriodMinerStockNotificationServiceInterface $service)
    {
        $this->service = $service;

        parent::__construct();
    }

    protected function configure()
    {
        $this->setName('app:end-rent-period-miner-stock-notification')
            ->setDescription('end rent period miner stock notification')
            ->setHelp('Notification end of the rental period');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        try {
            $this->service->execute();
        } catch (\Exception $e) {}

        return 1;
    }
}