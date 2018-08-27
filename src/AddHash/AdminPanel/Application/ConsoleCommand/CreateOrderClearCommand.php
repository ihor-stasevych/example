<?php

namespace App\AddHash\AdminPanel\Application\ConsoleCommand;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use App\AddHash\AdminPanel\Domain\Store\Order\Exceptions\StoreOrderNoUnPaidErrorException;
use App\AddHash\AdminPanel\Domain\Store\Order\Services\StoreOrderUnReserveMinerServiceInterface;
use App\AddHash\AdminPanel\Domain\Store\Order\Exceptions\StoreOrderNoUnReserveMinersErrorException;

class CreateOrderClearCommand extends Command
{
    private $service;

    public function __construct(StoreOrderUnReserveMinerServiceInterface $service)
    {
        $this->service = $service;

        parent::__construct();
    }

    protected function configure()
    {
        $this->setName('app:order-clear')
            ->setDescription('Order clear')
            ->setHelp('Clears and removes the reserve from the miners of those orders that were not paid for');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        try {
            $this->service->execute();
        } catch (StoreOrderNoUnPaidErrorException | StoreOrderNoUnReserveMinersErrorException $e) {

        }

        return 1;
    }
}