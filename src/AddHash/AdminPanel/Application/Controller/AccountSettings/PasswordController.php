<?php

namespace App\AddHash\AdminPanel\Application\Controller\AccountSettings;

use Swagger\Annotations as SWG;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use App\AddHash\System\GlobalContext\Common\BaseServiceController;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use App\AddHash\AdminPanel\Application\Command\User\AccountSettings\PasswordUpdateCommand;
use App\AddHash\AdminPanel\Domain\User\Exceptions\AccountSettings\PasswordIsNotValidException;
use App\AddHash\AdminPanel\Domain\User\Services\AccountSettings\PasswordUpdateServiceInterface;

class PasswordController extends BaseServiceController
{
    private $updateService;

    private $tokenStorage;

    public function __construct(PasswordUpdateServiceInterface $updateService, TokenStorageInterface $tokenStorage)
    {
        $this->updateService = $updateService;
        $this->tokenStorage = $tokenStorage;
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

        if (!$this->commandIsValid($command)) {
            return $this->json([
                'errors' => $this->getLastValidationErrors(),
            ], Response::HTTP_BAD_REQUEST);
        }

        try {
            $this->updateService->execute($command);
        } catch (PasswordIsNotValidException $e) {
            return $this->json([
                'errors' => $e->getMessage(),
            ], Response::HTTP_BAD_REQUEST);
        }

        return $this->json([]);
	}
}