<?php

namespace App\AddHash\AdminPanel\Application\Controller\AccountSettings;

use Swagger\Annotations as SWG;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use App\AddHash\System\GlobalContext\Common\BaseServiceController;
use App\AddHash\AdminPanel\Application\Command\User\AccountSettings\WalletUpdateCommand;
use App\AddHash\AdminPanel\Domain\User\Services\AccountSettings\WalletGetServiceInterface;
use App\AddHash\AdminPanel\Domain\User\Services\AccountSettings\WalletUpdateServiceInterface;
use App\AddHash\AdminPanel\Domain\User\Exceptions\AccountSettings\UserWalletIsNotValidException;

class WalletController extends BaseServiceController
{
    private $updateService;

    private $getService;

    public function __construct(
        WalletUpdateServiceInterface $updateService,
        WalletGetServiceInterface $getService
    )
    {
        $this->updateService = $updateService;
        $this->getService = $getService;
    }

    public function get()
    {
    	$user = $this->getService->execute();
        return $this->json($user);
    }

    public function update(Request $request)
	{
        $command = new WalletUpdateCommand($request->get('wallets'));

        if (!$this->commandIsValid($command)) {
            return $this->json([
                'errors' => $this->getLastValidationErrors(),
            ], Response::HTTP_BAD_REQUEST);
        }

        try {
            return $this->json($this->updateService->execute($command));
        } catch (UserWalletIsNotValidException $e) {
            return $this->json([
                'errors' => $e->getMessage(),
            ], Response::HTTP_BAD_REQUEST);
        }
	}

	public function create(Request $request)
    {
        $request->get('id');
        $request->get('name');

    }
}