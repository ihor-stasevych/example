<?php

namespace App\AddHash\AdminPanel\Application\Controller\AccountSettings;

use Swagger\Annotations as SWG;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use App\AddHash\System\GlobalContext\Common\BaseServiceController;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use App\AddHash\AdminPanel\Application\Command\User\AccountSettings\WalletUpdateCommand;
use App\AddHash\AdminPanel\Domain\User\Services\AccountSettings\WalletUpdateServiceInterface;

class WalletController extends BaseServiceController
{
    private $updateService;

    private $tokenStorage;

    public function __construct(WalletUpdateServiceInterface $updateService, TokenStorageInterface $tokenStorage)
    {
        $this->updateService = $updateService;
        $this->tokenStorage = $tokenStorage;
    }

    public function update(Request $request)
	{
        $command = new WalletUpdateCommand(
            $request->get('wallets'),
            $this->tokenStorage->getToken()->getUser()
        );

        if (!$this->commandIsValid($command)) {
            return $this->json([
                'errors' => $this->getLastValidationErrors()
            ], Response::HTTP_BAD_REQUEST);
        }

        $this->updateService->execute($command);

        return $this->json([]);
	}
}