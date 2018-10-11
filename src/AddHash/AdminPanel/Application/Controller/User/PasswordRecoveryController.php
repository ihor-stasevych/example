<?php

namespace App\AddHash\AdminPanel\Application\Controller\User;

use App\AddHash\AdminPanel\Application\Command\User\PaswordRecovery\UserPasswordRecoveryCommand;
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
	 * @SWG\Parameter(
	 *     name="hash",
	 *     in="query",
	 *     type="string",
	 *     required=true,
	 *     description="Hash"
	 * )
	 * @SWG\Parameter(
	 *     name="password",
	 *     in="body",
	 *     type="string",
	 *     required=true,
	 *     description="New password"
	 * )
	 *
	 * @SWG\Parameter(
	 *     name="confirmPassword",
	 *     in="body",
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
	 * @param string $hash
	 * @param Request $request
	 * @return \Symfony\Component\HttpFoundation\JsonResponse
	 */
	public function recoveryPassword($hash, Request $request)
	{
		$command = new UserPasswordRecoveryCommand(
			$hash,
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

	public function checkRecoveryHash($hash)
	{

	}

	//public function validateHash()
}