<?php

namespace App\AddHash\MinerPanel\Infrastructure\Queue\Miner\MinerPool;

use Interop\Queue\PsrContext;
use Interop\Queue\PsrMessage;
use Interop\Queue\PsrProcessor;
use Enqueue\Consumption\QueueSubscriberInterface;
use App\AddHash\MinerPanel\Application\Command\Miner\MinerPool\MinerPoolCreateCommand;
use App\AddHash\MinerPanel\Domain\Miner\MinerPool\Services\MinerPoolCreateServiceInterface;

class MinerPoolCreateQueueSubscriber implements PsrProcessor, QueueSubscriberInterface
{
    private $minerPoolCreateService;

    public function __construct(MinerPoolCreateServiceInterface $minerPoolCreateService)
    {
        $this->minerPoolCreateService = $minerPoolCreateService;
    }

    public function process(PsrMessage $message, PsrContext $context)
    {
        $data = json_decode($message->getBody(), true);

        if (!$data) {
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
        }

        return $status;
    }

    public static function getSubscribedQueues()
    {
        return ['pool.create'];
    }
}