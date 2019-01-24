<?php

namespace App\AddHash\MinerPanel\Infrastructure\Queue\Miner\MinerPool;

use App\AddHash\MinerPanel\Application\Command\Miner\MinerPool\MinerPoolCreateCommand;
use App\AddHash\MinerPanel\Domain\Miner\MinerPool\Services\MinerPoolCreateServiceInterface;
use Interop\Queue\PsrContext;
use Interop\Queue\PsrMessage;
use Interop\Queue\PsrProcessor;
use Enqueue\Consumption\QueueSubscriberInterface;

class MinerPoolCreateQueueSubscriber implements PsrProcessor, QueueSubscriberInterface
{
    private $minerPoolCreateService;

    public function __construct(MinerPoolCreateServiceInterface $minerPoolCreateService)
    {
        $this->minerPoolCreateService = $minerPoolCreateService;
    }

    /**
     * The method has to return either self::ACK, self::REJECT, self::REQUEUE string.
     *
     * The method also can return an object.
     * It must implement __toString method and the method must return one of the constants from above.
     *
     * @param PsrMessage $message
     * @param PsrContext $context
     *
     * @return string|object with __toString method implemented
     */
    public function process(PsrMessage $message, PsrContext $context)
    {
        $data = json_decode($message->getBody(), true);

        $status = self::ACK;

        try {
            $command = new MinerPoolCreateCommand($data['minerId'], $data['pools']);

            $this->minerPoolCreateService->execute($command);
        } catch (\Exception $e) {
            $status = self::REJECT;
        }

        return $status;
    }

    public static function getSubscribedQueues()
    {
        return ['pool.create'];
    }
}