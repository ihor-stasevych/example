<?php

namespace App\AddHash\MinerPanel\Application\Controller;

use Swagger\Annotations as SWG;
use Symfony\Component\HttpFoundation\JsonResponse;
use App\AddHash\System\GlobalContext\Common\BaseServiceController;
use App\AddHash\MinerPanel\Application\Command\Miner\MinerAlgorithm\MinerCoin\MinerCoinGetCommand;
use App\AddHash\MinerPanel\Domain\Miner\MinerAlgorithm\MinerCoin\Services\MinerCoinGetServiceInterface;

class MinerCoinController extends BaseServiceController
{
    private $getService;

    public function __construct(MinerCoinGetServiceInterface $getService)
    {
        $this->getService = $getService;
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