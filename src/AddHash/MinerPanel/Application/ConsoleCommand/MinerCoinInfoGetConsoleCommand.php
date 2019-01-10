<?php

namespace App\AddHash\MinerPanel\Application\ConsoleCommand;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use App\AddHash\MinerPanel\Domain\Miner\MinerAlgorithm\MinerCoin\Services\MinerCoinInfoGetServiceInterface;

class MinerCoinInfoGetConsoleCommand extends Command
{
    private $getService;

    public function __construct(MinerCoinInfoGetServiceInterface $getService)
    {
        $this->getService = $getService;

        parent::__construct();
    }

    protected function configure()
    {
        $this->setName('app:miner-coin-info-get')
            ->setDescription('Get coin info')
            ->setHelp('Get coin info');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $status = 1;

        try {
            $this->getService->execute();
        } catch (\Exception $e) {
            $status = 0;
        }

        return $status;
    }
}