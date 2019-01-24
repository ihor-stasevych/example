<?php

namespace App\AddHash\System\GlobalContext\Common;

use Interop\Amqp\AmqpContext;
use Interop\Amqp\Impl\AmqpBind;

class QueueProducer
{
	/** @var AmqpContext */
	private $context;
	private $topicName;
	private $topic;
	private $queueName;
	private $queue;
	private $message;

	/**
	 * QueueProducer constructor.
	 *
	 * @param $context
	 */
	public function __construct($context)
	{
		$this->context = $context;
	}

	/**
	 * @param $topic
	 * @param string $type
	 * @return $this
	 */
	public function createTopic($topic, $type = AMQP_EX_TYPE_FANOUT)
	{
		$this->topicName = $topic;
		$this->topic = $this->context->createTopic($this->topicName);
		$this->topic->setType($type);
		return $this;
	}

	/**
	 * @param $queue
	 * @return $this
	 */
	public function createQueue($queue)
	{
		$this->queueName = $queue;
		$this->queue = $this->context->createQueue($this->queueName);
		return $this;
	}

    /**
     * @param $message
     * @param $routingKey
     * @return $this
     */
	public function prepareMessage($message, $routingKey = null)
	{
		$this->message = $this->context->createMessage($message);
		$this->context->declareTopic($this->topic);
		$this->context->declareQueue($this->queue);

		$this->context->bind(new AmqpBind($this->topic, $this->queue, $routingKey));
		return $this;
	}

	/**
	 * @throws \Interop\Queue\Exception
	 * @throws \Interop\Queue\InvalidDestinationException
	 * @throws \Interop\Queue\InvalidMessageException
	 */
	public function send()
	{
		$this->context->createProducer()->send($this->topic, $this->message);
	}
}