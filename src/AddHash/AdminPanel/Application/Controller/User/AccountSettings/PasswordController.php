<?php

namespace App\AddHash\AdminPanel\Application\Controller\User\AccountSettings;

use Swagger\Annotations as SWG;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use App\AddHash\System\GlobalContext\Common\BaseServiceController;
use App\AddHash\AdminPanel\Application\Command\User\AccountSettings\PasswordUpdateCommand;
use App\AddHash\AdminPanel\Domain\User\Services\AccountSettings\PasswordUpdateServiceInterface;

class PasswordController extends BaseServiceController
{
    private $updateService;

    public function __construct(PasswordUpdateServiceInterface $updateService)
    {
        $this->updateService = $updateService;
    }

    /**
     * Update password by authorized user
     *
     * @SWG\Parameter(
     *     name="currentPassword",
     *     in="query",
     *     type="string",
     *     description="Current password"
     * )
     * @SWG\Parameter(
     *     name="newPassword",
     *     in="query",
     *     type="string",
     *     description="New password"
     * )
     * @SWG\Parameter(
     *     name="confirmNewPassword",
     *     in="query",
     *     type="string",
     *     description="Confirm new password"
     * )
     *
     * @SWG\Response(
     *     response=200,
     *     description="Returns success"
     * )
     *
     * @SWG\Response(
     *     response=406,
     *     description="Returns validation errors"
     * )
     *
     * @SWG\Response(
     *     response=400,
     *     description="Returns validation errors"
     * )
     *
     * @param Request $request
     * @return JsonResponse
     * @SWG\Tag(name="User")
     */
    public function update(Request $request)
	{
	    $command = new PasswordUpdateCommand(
            $request->get('currentPassword'),
            $request->get('newPassword'),
            $request->get('confirmNewPassword')
        );

        if (false === $this->commandIsValid($command)) {
            return $this->json([
                'errors' => $this->getLastValidationErrors(),
            ], Response::HTTP_NOT_ACCEPTABLE);
        }

        $this->updateService->execute($command);

        return $this->json([]);
	}
}