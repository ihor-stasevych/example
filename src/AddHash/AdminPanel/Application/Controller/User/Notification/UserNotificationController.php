<?php

namespace App\AddHash\AdminPanel\Application\Controller\User\Notification;

use App\AddHash\AdminPanel\Application\Command\User\Notification\GetNotificationCommand;
use App\AddHash\AdminPanel\Domain\User\Services\Notification\GetUserNotificationServiceInterface;
use App\AddHash\AdminPanel\Domain\User\Services\Notification\SendUserNotificationServiceInterface;
use App\AddHash\System\GlobalContext\Common\BaseServiceController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Swagger\Annotations as SWG;

class UserNotificationController extends BaseServiceController
{
	private $notificationService;
	private $getNotificationService;

	public function __construct(
		//SendUserNotificationServiceInterface $notificationService,
		GetUserNotificationServiceInterface $getUserNotificationService
	)
	{
		//$this->notificationService = $notificationService;
		$this->getNotificationService = $getUserNotificationService;
	}


	/**
	 * Get new user notifications
	 *
	 * @SWG\Response(
	 *     response=200,
	 *     description="Returns new user notifications"
	 * )
	 *
	 * @SWG\Tag(name="User Notification")
	 * @param Request $request
	 * @return mixed
	 */
	public function getNewNotifications(Request $request)
	{
		$command = new GetNotificationCommand($request->get('limit'));
		return $this->json($this->getNotificationService->execute($command));
	}
}