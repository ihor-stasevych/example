<?php

namespace App\AddHash\AdminPanel\Application\Controller\User;

use App\AddHash\AdminPanel\Application\Command\User\PaswordRecovery\UserPasswordRecoveryCommand;
use App\AddHash\AdminPanel\Application\Command\User\PaswordRecovery\UserPasswordRecoveryHashCommand;
use App\AddHash\AdminPanel\Application\Command\User\PaswordRecovery\UserPasswordRecoveryRequestCommand;
use App\AddHash\AdminPanel\Domain\User\Services\Notification\Email\SendUserResetPasswordEmailServiceInterface;
use App\AddHash\AdminPanel\Domain\User\Services\UserPasswordRecoveryServiceInterface;
use App\AddHash\System\GlobalContext\Common\BaseServiceController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Swagger\Annotations as SWG;

class PasswordRecoveryController extends BaseServiceController
{
	private $resetPasswordEmailService;
	private $passwordRecoveryService;

	public function __construct(
		SendUserResetPasswordEmailServiceInterface $resetPasswordEmailService,
		UserPasswordRecoveryServiceInterface $passwordRecoveryService
	)
	{
		$this->resetPasswordEmailService = $resetPasswordEmailService;
		$this->passwordRecoveryService = $passwordRecoveryService;
	}


	/**
	 * Sends the user a link with a password reset
	 *
	 * @SWG\Parameter(
	 *     name="email",
	 *     in="query",
	 *     type="string",
	 *     required=true,
	 *     description="User email"
	 * )
	 *
	 * @SWG\Response(
	 *     response=200,
	 *     description="Sends e-mail the user a link with a password reset"
	 * )
	 *
	 * @SWG\Tag(name="User")
	 *
	 * @param Request $request
	 * @return \Symfony\Component\HttpFoundation\JsonResponse
	 */
	public function sendEmailToResetPassword(Request $request)
	{
		$command = new UserPasswordRecoveryRequestCommand($request->get('email'));

		if (!$this->commandIsValid($command)) {
			return $this->json([
				'errors' => $this->getLastValidationErrors()
			], Response::HTTP_BAD_REQUEST);
		}

		try {
			$this->resetPasswordEmailService->execute($command);
		} catch (\Exception $e) {
			return $this->json([
				'errors' => $e->getMessage()
			], Response::HTTP_BAD_REQUEST);
		}

		return $this->json([]);

	}

	/**
	 * Changes user password by token
	 *
	 *
	 * @SWG\Parameter(
	 *     name="hash",
	 *     in="query",
	 *     type="string",
	 *     required=true,
	 *     description="Hash string"
	 * )
	 *
	 * @SWG\Parameter(
	 *     name="password",
	 *     in="query",
	 *     type="string",
	 *     required=true,
	 *     description="New password"
	 * )
	 *
	 * @SWG\Parameter(
	 *     name="confirmPassword",
	 *     in="query",
	 *     type="string",
	 *     required=true,
	 *     description="Confirm new password"
	 * )
	 *
	 * @SWG\Response(
	 *     response=200,
	 *     description="Changes user password by reset token"
	 * )
	 *
	 * @SWG\Tag(name="User")
	 *
	 * @param Request $request
	 * @return \Symfony\Component\HttpFoundation\JsonResponse
	 */
	public function recoveryPassword(Request $request)
	{
		$command = new UserPasswordRecoveryCommand(
			$request->get('hash'),
			$request->get('password'),
			$request->get('confirmPassword')
		);

		if (!$this->commandIsValid($command)) {
			return $this->json([
				'errors' => $this->getLastValidationErrors()
			], Response::HTTP_BAD_REQUEST);
		}

		try {
			$this->passwordRecoveryService->execute($command);
		} catch (\Exception $e) {
			return $this->json([
				'errors' => $e->getMessage()
			], Response::HTTP_BAD_REQUEST);
		}

		return $this->json([]);
	}

	/**
	 * Checking hash for password recovery
	 *
	 * @SWG\Parameter(
	 *     name="hash",
	 *     in="query",
	 *     type="string",
	 *     required=true,
	 *     description="Hash string"
	 * )
	 *
	 * @SWG\Response(
	 *     response=200,
	 *     description="Checking hash for password recovery"
	 * )
	 *
	 * @SWG\Tag(name="User")
	 *
	 * @param $request Request
	 * @return \Symfony\Component\HttpFoundation\JsonResponse
	 */
	public function checkRecoveryHash(Request $request)
	{
		$command = new UserPasswordRecoveryHashCommand($request->get('hash'));

		if (!$this->commandIsValid($command)) {
			return $this->json([
				'errors' => $this->getLastValidationErrors()
			], Response::HTTP_BAD_REQUEST);
		}

		try {
			$this->passwordRecoveryService->ensureHash($command->getHash());
		} catch (\Exception $e) {
			return $this->json([
				'errors' => $e->getMessage()
			], Response::HTTP_BAD_REQUEST);
		}

		return $this->json([]);
	}
}