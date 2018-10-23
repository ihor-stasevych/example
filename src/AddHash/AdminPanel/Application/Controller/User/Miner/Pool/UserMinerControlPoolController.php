<?php

namespace App\AddHash\AdminPanel\Application\Controller\User\Miner\Pool;

use Swagger\Annotations as SWG;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use App\AddHash\System\GlobalContext\Common\BaseServiceController;
use App\AddHash\AdminPanel\Application\Command\User\Miner\Pool\UserMinerControlPoolCommand;
use App\AddHash\AdminPanel\Domain\User\Services\Miner\Pool\UserMinerControlPoolContextInterface;
use App\AddHash\AdminPanel\Application\Command\User\Miner\Pool\UserMinerControlPoolCreateCommand;
use App\AddHash\AdminPanel\Infrastructure\Services\User\Miner\Pool\Strategy\UserMinerControlPoolGetStrategy;
use App\AddHash\AdminPanel\Infrastructure\Services\User\Miner\Pool\Strategy\UserMinerControlPoolCreateStrategy;

class UserMinerControlPoolController extends BaseServiceController
{
    private $contextPool;

    public function __construct(UserMinerControlPoolContextInterface $contextPool)
    {
        $this->contextPool = $contextPool;
    }

    /**
     * Get miner pools
     *
     * @SWG\Response(
     *     response=200,
     *     description="Returns the miner pools",
     *     @SWG\Schema(
     *          type="array",
     *          @SWG\Items(
     *              type="object",
     *              @SWG\Property(property="Status", type="string"),
     *              @SWG\Property(property="Priority", type="integer")
     *          )
     *     ),
     * )
     *
     * @SWG\Response(
     *     response=400,
     *     description="Returns validation errors"
     * )
     *
     * @param int $id
     * @return JsonResponse
     * @SWG\Tag(name="User")
     */
    public function get(int $id)
    {
        $command = new UserMinerControlPoolCommand($id);

        return $this->json(
            $this->contextPool->handle(UserMinerControlPoolGetStrategy::STRATEGY_ALIAS, $command)
        );
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
     *          type="array",
     *          @SWG\Items(
     *              type="object",
     *              @SWG\Property(property="Status", type="string"),
     *              @SWG\Property(property="Priority", type="integer")
     *          )
     *     ),
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
     * @param int $id
     * @param Request $request
     * @return JsonResponse
     * @SWG\Tag(name="User")
     */
    public function create(int $id, Request $request)
    {
        $command = new UserMinerControlPoolCreateCommand($id, $request->get('pools'));

        if (false === $this->commandIsValid($command)) {
            return $this->json([
                'errors' => $this->getLastValidationErrors(),
            ], Response::HTTP_NOT_ACCEPTABLE);
        }

        return $this->json($this->contextPool->handle(UserMinerControlPoolCreateStrategy::STRATEGY_ALIAS, $command));
    }
}