<?php

namespace App\AddHash\AdminPanel\Application\Controller\User\Miner\Rig;

use Swagger\Annotations as SWG;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use App\AddHash\System\GlobalContext\Common\BaseServiceController;
use App\AddHash\AdminPanel\Application\Command\User\Miner\Rig\UserMinerControlRigGetCommand;
use App\AddHash\AdminPanel\Application\Command\User\Miner\Rig\UserMinerControlRigUpdateCommand;
use App\AddHash\AdminPanel\Application\Command\User\Miner\Rig\UserMinerControlRigDeleteCommand;
use App\AddHash\AdminPanel\Application\Command\User\Miner\Rig\UserMinerControlRigCreateCommand;
use App\AddHash\AdminPanel\Domain\User\Services\Miner\Rig\UserMinerControlRigCreateServiceInterface;

class UserMinerControlRigController extends BaseServiceController
{
    private $createService;

    public function __construct(UserMinerControlRigCreateServiceInterface $createService)
    {
        $this->createService = $createService;
    }

    public function index()
    {
        $data = [];

        return $this->json($data);
    }

    public function get(int $id)
    {
        $command = new UserMinerControlRigGetCommand($id);

        try {
            $data = [];
        } catch (\Exception $e) {
            return $this->json([
                'errors' => $e->getMessage(),
            ], $e->getCode());
        }

        return $this->json($data);
    }

    public function create(Request $request)
    {
        $command = new UserMinerControlRigCreateCommand(
            $request->get('minersId'),
            $request->get('name'),
            $request->get('worker'),
            $request->get('url')
        );

        if (!$this->commandIsValid($command)) {
            return $this->json([
                'errors' => $this->getLastValidationErrors(),
            ], Response::HTTP_BAD_REQUEST);
        }

        try {
            $data = $this->createService->execute($command);
        } catch (\Exception $e) {
            return $this->json([
                'errors' => $e->getMessage(),
            ], Response::HTTP_BAD_REQUEST);
        }

        return $this->json($data);
    }

    public function update(int $id, Request $request)
    {
        $command = new UserMinerControlRigUpdateCommand(
            $id,
            $request->get('minersId'),
            $request->get('name'),
            $request->get('worker'),
            $request->get('url')
        );

        if (!$this->commandIsValid($command)) {
            return $this->json([
                'errors' => $this->getLastValidationErrors(),
            ], Response::HTTP_BAD_REQUEST);
        }

        try {
            $data = [];
        } catch (\Exception $e) {
            return $this->json([
                'errors' => $e->getMessage(),
            ], Response::HTTP_BAD_REQUEST);
        }

        return $this->json($data);
    }

    public function delete(int $id)
    {
        $command = new UserMinerControlRigDeleteCommand($id);

        if (!$this->commandIsValid($command)) {
            return $this->json([
                'errors' => $this->getLastValidationErrors(),
            ], Response::HTTP_BAD_REQUEST);
        }

        try {
            $data = [];
        } catch (\Exception $e) {
            return $this->json([
                'errors' => $e->getMessage(),
            ], Response::HTTP_BAD_REQUEST);
        }

        return $this->json($data);
    }
}