<?php

namespace App\AddHash\AdminPanel\Application\Controller\User\Notification;

use App\AddHash\AdminPanel\Application\Command\User\Notification\GetNotificationCommand;
use App\AddHash\AdminPanel\Application\Command\User\Notification\MarkAsReadNotificationCommand;
use App\AddHash\AdminPanel\Domain\User\Services\Notification\GetUserNotificationServiceInterface;
use App\AddHash\AdminPanel\Domain\User\Services\Notification\MarkAsReadNotificationServiceInterface;
use App\AddHash\AdminPanel\Domain\User\Services\Notification\SendUserNotificationServiceInterface;
use App\AddHash\System\GlobalContext\Common\BaseServiceController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Swagger\Annotations as SWG;

class UserNotificationController extends BaseServiceController
{
	private $notificationService;
	private $getNotificationService;
	private $markAsReadNotificationService;

	public function __construct(
		//SendUserNotificationServiceInterface $notificationService,
		GetUserNotificationServiceInterface $getUserNotificationService,
		MarkAsReadNotificationServiceInterface $markAsReadNotificationService
	)
	{
		//$this->notificationService = $notificationService;
		$this->getNotificationService = $getUserNotificationService;
		$this->markAsReadNotificationService = $markAsReadNotificationService;
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

	/**
	 *
	 * Mark as read notifications
	 *
	 * @SWG\Parameter(
	 *     name="notifications",
	 *     in="body",
	 *     type="array",
	 *     @SWG\Schema(
	 *         type="array",
	 *         @SWG\Items(type="integer")
	 *     ),
	 *     required=true,
	 *     description="ids of the notifications"
	 * )
	 *
	 * @SWG\Tag(name="User Notification")
	 * @SWG\Response(
	 *     response=200,
	 *     description="Returns ok"
	 * )
	 *
	 * @param Request $request
	 * @return bool|\Symfony\Component\HttpFoundation\JsonResponse
	 */
	public function markAsRead(Request $request)
	{
		$command = new MarkAsReadNotificationCommand($request->get('notifications'));

		if (!$this->commandIsValid($command)) {
			return $this->json([
				'errors' => $this->getLastValidationErrors()
			], Response::HTTP_BAD_REQUEST);
		}

		$this->markAsReadNotificationService->execute($command);

		return $this->json([]);
	}
}