<?php

namespace App\AddHash\AdminPanel\Application\Controller\User\Miner\Pool;

use Swagger\Annotations as SWG;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use App\AddHash\System\GlobalContext\Common\BaseServiceController;
use App\AddHash\AdminPanel\Domain\User\Exceptions\MinerControlNoMainerException;
use App\AddHash\AdminPanel\Domain\User\Exceptions\MinerControlNoMainerExistException;
use App\AddHash\AdminPanel\Application\Command\User\Miner\Pool\UserMinerControlPoolGetCommand;
use App\AddHash\AdminPanel\Application\Command\User\Miner\Pool\UserMinerControlPoolCreateCommand;
use App\AddHash\AdminPanel\Domain\User\Services\Miner\Strategy\UserMinerControlStrategyInterface;
use App\AddHash\AdminPanel\Domain\User\Services\Miner\Pool\UserMinerControlPoolGetServiceInterface;
use App\AddHash\AdminPanel\Domain\User\Services\Miner\Pool\UserMinerControlPoolCreateServiceInterface;

class UserMinerControlPoolController extends BaseServiceController
{
    private $strategy;

    private $getService;

    private $createService;

    public function __construct(
        UserMinerControlStrategyInterface $strategy,
        UserMinerControlPoolGetServiceInterface $getService,
        UserMinerControlPoolCreateServiceInterface $createService
    )
    {
        $this->strategy = $strategy;
        $this->getService = $getService;
        $this->createService = $createService;
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
            $data = $this->strategy->execute($command, $this->getService);
        } catch (MinerControlNoMainerException | MinerControlNoMainerExistException $e) {
            return $this->json([
                'errors' => $e->getMessage(),
            ], Response::HTTP_BAD_REQUEST);
        }

        return $this->json($data);
    }

    /**
     * Create/Update/Delete miner pools
     *
     * @SWG\Parameter(
     *     name="pools",
     *     in="body",
     *     description="",
     *     required=true,
     *     @SWG\Schema(
     *         type="array",
     *         @SWG\Items(
     *            type="object",
     *            @SWG\Property(property="user", type="string"),
     *            @SWG\Property(property="url", type="string"),
     *            @SWG\Property(property="password", type="string"),
     *            @SWG\Property(property="status", type="integer"),
     *         )
     *     )
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
        $command = new UserMinerControlPoolCreateCommand($id, $request->get('pools'));

        if (!$this->commandIsValid($command)) {
            return $this->json([
                'errors' => $this->getLastValidationErrors(),
            ], Response::HTTP_BAD_REQUEST);
        }

        try {
            $data = $this->strategy->execute($command, $this->createService);
        } catch (MinerControlNoMainerException $e) {
            return $this->json([
                'errors' => $e->getMessage(),
            ], Response::HTTP_BAD_REQUEST);
        }

        return $this->json($data);
    }
}