<?php

namespace App\AddHash\MinerPanel\Infrastructure\Queue\Miner\MinerPool;

use Interop\Queue\PsrContext;
use Interop\Queue\PsrMessage;
use Interop\Queue\PsrProcessor;
use Psr\Log\LoggerInterface;
use Enqueue\Consumption\QueueSubscriberInterface;
use App\AddHash\MinerPanel\Application\Command\Miner\MinerPool\MinerPoolCreateCommand;
use App\AddHash\MinerPanel\Domain\Miner\MinerPool\Services\MinerPoolCreateServiceInterface;

class MinerPoolCreateQueueSubscriber implements PsrProcessor, QueueSubscriberInterface
{
    private $minerPoolCreateService;
    private $logger;

    public function __construct(
    	MinerPoolCreateServiceInterface $minerPoolCreateService,
		LoggerInterface $logger

    )
    {
        $this->minerPoolCreateService = $minerPoolCreateService;
        $this->logger = $logger;
    }

    public function process(PsrMessage $message, PsrContext $context)
    {

    	$this->logger->debug('PoolCreateQueue income message', [$message->getBody()]);

        $data = json_decode($message->getBody(), true);

        if (!$data) {
	        $this->logger->error('PoolCreateQueue error message',  [$message->getBody()]);
        	echo 'Incorrect json format message: '. var_dump($message->getBody());
        	return self::REJECT;
        }

        $status = self::ACK;

        try {
            $command = new MinerPoolCreateCommand($data['minerId'], $data['pools']);

            $this->minerPoolCreateService->execute($command);
        } catch (\Exception $e) {
            $status = self::REJECT;
            var_dump('Exception' . $e->getMessage());
	        $this->logger->error('PoolCreateQueue result error message', $e->getMessage());
        }

        return $status;
    }

    public static function getSubscribedQueues()
    {
        return ['pool.create'];
    }
}