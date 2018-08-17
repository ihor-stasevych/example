<?php

namespace App\AddHash\AdminPanel\Application\Controller\User\Miner\Pool;

use Swagger\Annotations as SWG;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use App\AddHash\System\GlobalContext\Common\BaseServiceController;
use App\AddHash\AdminPanel\Domain\User\Exceptions\MinerControlNoMainerException;
use App\AddHash\AdminPanel\Domain\User\Exceptions\MinerControlPoolNoAddedException;
use App\AddHash\AdminPanel\Domain\User\Exceptions\MinerControlPoolNoDeleteException;
use App\AddHash\AdminPanel\Domain\User\Exceptions\MinerControlMaxCountPoolsException;
use App\AddHash\AdminPanel\Domain\User\Exceptions\MinerControlNoMainerExistException;
use App\AddHash\AdminPanel\Domain\User\Exceptions\MinerControlPoolNoChangeStatusException;
use App\AddHash\AdminPanel\Application\Command\User\Miner\Pool\UserMinerControlPoolGetCommand;
use App\AddHash\AdminPanel\Application\Command\User\Miner\Pool\UserMinerControlPoolUpdateCommand;
use App\AddHash\AdminPanel\Application\Command\User\Miner\Pool\UserMinerControlPoolCreateCommand;
use App\AddHash\AdminPanel\Application\Command\User\Miner\Pool\UserMinerControlPoolDeleteCommand;
use App\AddHash\AdminPanel\Domain\User\Services\Miner\Pool\UserMinerControlPoolGetServiceInterface;
use App\AddHash\AdminPanel\Domain\User\Services\Miner\Pool\UserMinerControlPoolCreateServiceInterface;
use App\AddHash\AdminPanel\Domain\User\Services\Miner\Pool\UserMinerControlPoolDeleteServiceInterface;
use App\AddHash\AdminPanel\Domain\User\Services\Miner\Pool\UserMinerControlPoolUpdateServiceInterface;
use App\AddHash\AdminPanel\Application\Command\User\Miner\Pool\UserMinerControlPoolStatusUpdateCommand;
use App\AddHash\AdminPanel\Domain\User\Services\Miner\Pool\UserMinerControlPoolStatusUpdateServiceInterface;

class UserMinerControlPoolController extends BaseServiceController
{
    private $getService;

    private $createService;

    private $deleteService;

    private $updateService;

    private $updateStatusService;

    public function __construct(
        UserMinerControlPoolGetServiceInterface $getService,
        UserMinerControlPoolCreateServiceInterface $createService,
        UserMinerControlPoolDeleteServiceInterface $deleteService,
        UserMinerControlPoolUpdateServiceInterface $updateService,
        UserMinerControlPoolStatusUpdateServiceInterface $updateStatusService
    )
    {
        $this->getService = $getService;
        $this->createService = $createService;
        $this->deleteService = $deleteService;
        $this->updateService = $updateService;
        $this->updateStatusService = $updateStatusService;
    }

    /**
     * Get miner pools
     *
     * @SWG\Response(
     *     response=200,
     *     description="Returns the miner pools",
     *     @SWG\Schema(
     *             type="array",
     *             @SWG\Items(
     *                 type="object",
     *                 @SWG\Property(property="Status", type="string"),
     *                 @SWG\Property(property="Priority", type="integer")
     *             )
     *     ),
     * )
     *
     * @param int $id
     * @return JsonResponse
     * @SWG\Tag(name="User")
     */
    public function get(int $id)
    {
        $command = new UserMinerControlPoolGetCommand($id);

        if (!$this->commandIsValid($command)) {
            return $this->json([
                'errors' => $this->getLastValidationErrors(),
            ], Response::HTTP_BAD_REQUEST);
        }

        try {
            $data = $this->getService->execute($command);
        } catch (MinerControlNoMainerException | MinerControlNoMainerExistException $e) {
            return $this->json([
                'errors' => $e->getMessage(),
            ], Response::HTTP_BAD_REQUEST);
        }

        return $this->json($data);
    }

    /**
     * Create miner pool
     *
     * @SWG\Parameter(
     *     in="query",
     *     name="user",
     *     type="string",
     *     required=true,
     *     description="Worker",
     * )
     *
     * @SWG\Parameter(
     *     in="query",
     *     name="url",
     *     type="string",
     *     required=true,
     *     description="Url",
     * )
     *
     * @SWG\Parameter(
     *     in="query",
     *     name="password",
     *     type="string",
     *     required=true,
     *     description="Password",
     * )
     *
     * @SWG\Response(
     *     response=200,
     *     description="Returns the miner pools",
     *     @SWG\Schema(
     *             type="array",
     *             @SWG\Items(
     *                 type="object",
     *                 @SWG\Property(property="Status", type="string"),
     *                 @SWG\Property(property="Priority", type="integer")
     *             )
     *     ),
     * )
     *
     * @param int $id
     * @param Request $request
     * @return JsonResponse
     * @SWG\Tag(name="User")
     */
    public function create(int $id, Request $request)
    {
        $command = new UserMinerControlPoolCreateCommand(
            $id,
            $request->get('url'),
            $request->get('user'),
            $request->get('password')
        );

        if (!$this->commandIsValid($command)) {
            return $this->json([
                'errors' => $this->getLastValidationErrors(),
            ], Response::HTTP_BAD_REQUEST);
        }

        try {
            $data = $this->createService->execute($command);
        } catch (MinerControlMaxCountPoolsException | MinerControlPoolNoAddedException $e) {
            return $this->json([
                'errors' => $e->getMessage(),
            ], Response::HTTP_BAD_REQUEST);
        }

        return $this->json($data);
    }


    /**
     * Delete miner pool
     *
     * @SWG\Parameter(
     *     in="query",
     *     name="poolId",
     *     type="integer",
     *     required=true,
     *     description="Pool Id",
     * )
     *
     * @SWG\Response(
     *     response=200,
     *     description="Returns the miner pools",
     *     @SWG\Schema(
     *             type="array",
     *             @SWG\Items(
     *                 type="object",
     *                 @SWG\Property(property="Status", type="string"),
     *                 @SWG\Property(property="Priority", type="integer")
     *             )
     *     ),
     * )
     *
     * @param int $id
     * @param Request $request
     * @return JsonResponse
     * @SWG\Tag(name="User")
     */
    public function delete(int $id, Request $request)
    {
        $command = new UserMinerControlPoolDeleteCommand($id, $request->get('poolId'));

        if (!$this->commandIsValid($command)) {
            return $this->json([
                'errors' => $this->getLastValidationErrors(),
            ], Response::HTTP_BAD_REQUEST);
        }

        try {
            $data = $this->deleteService->execute($command);
        } catch (MinerControlPoolNoDeleteException $e) {
            return $this->json([
                'errors' => $e->getMessage(),
            ], Response::HTTP_BAD_REQUEST);
        }

        return $this->json($data);
    }

    public function update(int $id, Request $request)
    {
        $command = new UserMinerControlPoolUpdateCommand(
            $id,
            $request->get('poolId'),
            $request->get('url'),
            $request->get('user'),
            $request->get('password')
        );

        if (!$this->commandIsValid($command)) {
            return $this->json([
                'errors' => $this->getLastValidationErrors(),
            ], Response::HTTP_BAD_REQUEST);
        }

        try {
            $data = $this->updateService->execute($command);
        } catch (\Exception $e) {
            return $this->json([
                'errors' => $e->getMessage(),
            ], Response::HTTP_BAD_REQUEST);
        }

        return $this->json($data);
    }

    /**
     * Change status miner pool
     *
     * @SWG\Parameter(
     *     in="query",
     *     name="poolId",
     *     type="integer",
     *     required=true,
     *     description="Pool id",
     * )
     *
     * @SWG\Parameter(
     *     in="query",
     *     name="status",
     *     type="integer",
     *     required=true,
     *     description="1 or 0",
     * )
     *
     * @SWG\Response(
     *     response=200,
     *     description="Returns the miner pools",
     *     @SWG\Schema(
     *             type="array",
     *             @SWG\Items(
     *                 type="object",
     *                 @SWG\Property(property="Status", type="string"),
     *                 @SWG\Property(property="Priority", type="integer")
     *             )
     *     ),
     * )
     *
     * @param int $id
     * @param Request $request
     * @return JsonResponse
     * @SWG\Tag(name="User")
     */
    public function statusUpdate(int $id, Request $request)
    {
        $command = new UserMinerControlPoolStatusUpdateCommand(
            $id,
            $request->get('poolId'),
            $request->get('status')
        );

        if (!$this->commandIsValid($command)) {
            return $this->json([
                'errors' => $this->getLastValidationErrors(),
            ], Response::HTTP_BAD_REQUEST);
        }

        try {
            $data = $this->updateStatusService->execute($command);
        } catch (MinerControlPoolNoChangeStatusException $e) {
            return $this->json([
                'errors' => $e->getMessage(),
            ], Response::HTTP_BAD_REQUEST);
        }

        return $this->json($data);
    }
}