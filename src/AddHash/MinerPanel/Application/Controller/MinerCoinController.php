<?php

namespace App\AddHash\MinerPanel\Application\Controller;

use Swagger\Annotations as SWG;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use App\AddHash\System\GlobalContext\Common\BaseServiceController;
use App\AddHash\MinerPanel\Application\Command\Miner\MinerAlgorithm\MinerCoin\MinerCoinGetCommand;
use App\AddHash\MinerPanel\Application\Command\Miner\MinerAlgorithm\MinerCoin\MinerCoinListCommand;
use App\AddHash\MinerPanel\Domain\Miner\MinerAlgorithm\MinerCoin\Services\MinerCoinGetServiceInterface;
use App\AddHash\MinerPanel\Domain\Miner\MinerAlgorithm\MinerCoin\Services\MinerCoinListServiceInterface;
use App\AddHash\MinerPanel\Domain\Miner\MinerAlgorithm\MinerCoin\Exceptions\MinerCoinInvalidCommandException;

class MinerCoinController extends BaseServiceController
{
    private $getService;

    private $listService;

    public function __construct(
        MinerCoinGetServiceInterface $getService,
        MinerCoinListServiceInterface $listService
    )
    {
        $this->getService = $getService;
        $this->listService = $listService;
    }

    /**
     * Get coins
     *
     * @SWG\Parameter(
     *     name="page",
     *     in="query",
     *     type="integer",
     *     description="Page",
     * )
     *
     * @SWG\Response(
     *     response=200,
     *     description="Return miners",
     *     @SWG\Schema(
     *          type="object",
     *          @SWG\Property(
     *              property="items",
     *              type="array",
     *              @SWG\Items(
     *                  type="object",
     *                  @SWG\Property(property="id", type="integer"),
     *                  @SWG\Property(property="name", type="string"),
     *                  @SWG\Property(property="tag", type="string"),
     *                  @SWG\Property(property="algorithm", type="string"),
     *                  @SWG\Property(property="reward", type="number"),
     *                  @SWG\Property(property="difficulty", type="number"),
     *                  @SWG\Property(property="icon", type="string"),
     *                  @SWG\Property(property="cryptoToUsd", type="number"),
     *                  @SWG\Property(property="hashRate", type="number"),
     *              ),
     *          ),
     *          @SWG\Property(property="totalItems", type="integer"),
     *          @SWG\Property(property="totalPages", type="integer"),
     *          @SWG\Property(property="page", type="integer"),
     *          @SWG\Property(property="limit", type="integer"),
     *     ),
     * )
     *
     * @SWG\Response(
     *     response=400,
     *     description="Return validation errors"
     * )
     *
     * @param Request $request
     * @return JsonResponse
     * @throws MinerCoinInvalidCommandException
     * @SWG\Tag(name="MinerPanel_MinerCoin")
     */
    public function index(Request $request)
    {
        $command = new MinerCoinListCommand($request->get('page'));

        if (false === $this->commandIsValid($command)) {
            throw new MinerCoinInvalidCommandException('Invalid command');
        }

        return $this->json(
            $this->listService->execute($command)
        );
    }

    /**
     * Get miner coins
     *
     * @SWG\Parameter(
     *     name="id",
     *     in="path",
     *     type="integer",
     *     description="ID",
     *     required=true,
     * )
     *
     * @SWG\Response(
     *     response=200,
     *     description="Return miner coins",
     *     @SWG\Schema(
     *          type="array",
     *          @SWG\Items(
     *              type="object",
     *              @SWG\Property(property="name", type="string"),
     *              @SWG\Property(property="tag", type="string"),
     *              @SWG\Property(property="income", type="number"),
     *          )
     *     ),
     * )
     *
     * @SWG\Response(
     *     response=400,
     *     description="Return validation errors"
     * )
     *
     * @param int $id
     * @return JsonResponse
     * @SWG\Tag(name="MinerPanel_MinerCoin")
     */
    public function get(int $id)
    {
        $command = new MinerCoinGetCommand($id);

        return $this->json(
            $this->getService->execute($command)
        );
    }
}