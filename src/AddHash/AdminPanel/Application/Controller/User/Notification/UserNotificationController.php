<?php

namespace App\AddHash\AdminPanel\Application\Controller\User\Notification;

use Swagger\Annotations as SWG;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use App\AddHash\System\GlobalContext\Common\BaseServiceController;
use App\AddHash\AdminPanel\Application\Command\User\Notification\GetNotificationCommand;
use App\AddHash\AdminPanel\Application\Command\User\Notification\MarkAsReadNotificationCommand;
use App\AddHash\AdminPanel\Domain\User\Services\Notification\GetUserNotificationServiceInterface;
use App\AddHash\AdminPanel\Domain\User\Services\Notification\MarkAsReadNotificationServiceInterface;

class UserNotificationController extends BaseServiceController
{
	private $getNotificationService;

	private $markAsReadNotificationService;

	public function __construct(
		GetUserNotificationServiceInterface $getUserNotificationService,
		MarkAsReadNotificationServiceInterface $markAsReadNotificationService
	)
	{
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
     * @SWG\Response(
     *     response=400,
     *     description="Returns validation errors"
     * )
     * @SWG\Response(
     *     response=200,
     *     description="Returns ok"
     * )
     *
     * @param Request $request
     * @return JsonResponse
     * @SWG\Tag(name="User Notification")
     */
	public function markAsRead(Request $request)
	{
		$command = new MarkAsReadNotificationCommand($request->get('notifications'));

		if (false === $this->commandIsValid($command)) {
			return $this->json([
				'errors' => $this->getLastValidationErrors()
			], Response::HTTP_BAD_REQUEST);
		}

		$this->markAsReadNotificationService->execute($command);

		return $this->json([]);
	}
}