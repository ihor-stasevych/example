<?php

namespace App\AddHash\AdminPanel\Infrastructure\EventSubscribers\Store\Order;

use App\AddHash\AdminPanel\Domain\Store\Order\Event\StoreOrderPayedEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class StoreOrderEventSubscriber implements EventSubscriberInterface
{

	/**
	 * Returns an array of event names this subscriber wants to listen to.
	 * The array keys are event names and the value can be:
	 *  * The method name to call (priority defaults to 0)
	 *  * An array composed of the method name to call and the priority
	 *  * An array of arrays composed of the method names to call and respective
	 *    priorities, or 0 if unset
	 * For instance:
	 *  * array('eventName' => 'methodName')
	 *  * array('eventName' => array('methodName', $priority))
	 *  * array('eventName' => array(array('methodName1', $priority), array('methodName2')))
	 *
	 * @return array The event names to listen to
	 */
	public static function getSubscribedEvents()
	{
		return [StoreOrderPayedEvent::NAME => 'onOrderPayment'];
	}

	public function onOrderPayment(StoreOrderPayedEvent $event)
	{
		$event->getNotificationService()->setMessage(
			'Order with id: ' . $event->getOrder()->getId() . ' was payed by user: ' . $event->getOrder()->getUser()->getEmail()
		);

		$event->getNotificationService()->notify();
	}
}