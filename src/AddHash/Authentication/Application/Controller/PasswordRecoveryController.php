<?php

namespace App\AddHash\Authentication\Application\Controller;

use Swagger\Annotations as SWG;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use App\AddHash\System\GlobalContext\Common\BaseServiceController;
use App\AddHash\Authentication\Application\Command\UserPasswordRecoveryCommand;
use App\AddHash\Authentication\Application\Command\UserPasswordRecoveryHashCommand;
use App\AddHash\Authentication\Domain\Services\UserRecoveryPasswordServiceInterface;
use App\AddHash\Authentication\Domain\Services\UserCheckRecoveryHashServiceInterface;
use App\AddHash\Authentication\Application\Command\UserPasswordRecoveryRequestCommand;
use App\AddHash\Authentication\Domain\Services\UserSendResetPasswordEmailServiceInterface;

class PasswordRecoveryController extends BaseServiceController
{
    private $userSendResetPasswordEmailService;

    private $checkRecoveryHashService;

    private $userRecoveryPasswordService;

    public function __construct(
        UserSendResetPasswordEmailServiceInterface $userSendResetPasswordEmailService,
        UserCheckRecoveryHashServiceInterface $checkRecoveryHashService,
        UserRecoveryPasswordServiceInterface $userRecoveryPasswordService
    )
    {
        $this->userSendResetPasswordEmailService = $userSendResetPasswordEmailService;
        $this->checkRecoveryHashService = $checkRecoveryHashService;
        $this->userRecoveryPasswordService = $userRecoveryPasswordService;
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
     * @return JsonResponse
     */
    public function sendEmailToResetPassword(Request $request)
    {
        $command = new UserPasswordRecoveryRequestCommand($request->get('email'));

        if (false === $this->commandIsValid($command)) {
            return $this->json([
                'errors' => $this->getLastValidationErrors()
            ], Response::HTTP_BAD_REQUEST);
        }

        try {
            $this->userSendResetPasswordEmailService->execute($command);
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
     * @return JsonResponse
     */
    public function recoveryPassword(Request $request)
    {
        $command = new UserPasswordRecoveryCommand(
            $request->get('hash'),
            $request->get('password'),
            $request->get('confirmPassword')
        );

        if (false === $this->commandIsValid($command)) {
            return $this->json([
                'errors' => $this->getLastValidationErrors()
            ], Response::HTTP_BAD_REQUEST);
        }

        try {
            $this->userRecoveryPasswordService->execute($command);
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
     * @return JsonResponse
     */
    public function checkRecoveryHash(Request $request)
    {
        $command = new UserPasswordRecoveryHashCommand($request->get('hash'));

        if (false === $this->commandIsValid($command)) {
            return $this->json([
                'errors' => $this->getLastValidationErrors()
            ], Response::HTTP_BAD_REQUEST);
        }

        try {
            $this->checkRecoveryHashService->execute($command);
        } catch (\Exception $e) {
            return $this->json([
                'errors' => $e->getMessage()
            ], Response::HTTP_BAD_REQUEST);
        }

        return $this->json([]);
    }
}