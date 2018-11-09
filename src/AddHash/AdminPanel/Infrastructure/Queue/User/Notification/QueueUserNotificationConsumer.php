<?php

namespace App\AddHash\AdminPanel\Infrastructure\Queue\User\Notification;


use OldSound\RabbitMqBundle\RabbitMq\ConsumerInterface;
use PhpAmqpLib\Message\AMQPMessage;

class QueueUserNotificationConsumer implements ConsumerInterface
{

	/**
	 * @param AMQPMessage $msg The message
	 * @return mixed false to reject and requeue, any other value to acknowledge
	 */
	public function execute(AMQPMessage $msg)
	{
		$body = $msg->body;
		//var_dump($body);

		$response = json_decode($msg->body, true);

		var_dump($response);
	}
}