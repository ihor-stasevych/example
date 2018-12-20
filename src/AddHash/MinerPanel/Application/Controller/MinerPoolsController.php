<?php

namespace App\AddHash\MinerPanel\Application\Controller;

use Swagger\Annotations as SWG;
use Symfony\Component\HttpFoundation\JsonResponse;
use App\AddHash\System\GlobalContext\Common\BaseServiceController;
use App\AddHash\MinerPanel\Application\Command\Miner\MinerPools\MinerPoolsGetCommand;
use App\AddHash\MinerPanel\Domain\Miner\MinerPools\Services\MinerPoolsGetServiceInterface;

class MinerPoolsController extends BaseServiceController
{
    private $getService;

    public function __construct(MinerPoolsGetServiceInterface $getService)
    {
        $this->getService = $getService;
    }

    /**
     * Get miner pools
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
     *     description="Return miner pools",
     *     @SWG\Schema(
     *          type="object",
     *          @SWG\Property(property="url", type="string"),
     *          @SWG\Property(property="user", type="string"),
     *          @SWG\Property(property="status", type="string"),
     *     )
     * )
     *
     * @SWG\Response(
     *     response=400,
     *     description="Return validation errors"
     * )
     *
     * @param int $id
     * @return JsonResponse
     * @SWG\Tag(name="MinerPanel_MinerPools")
     */
    public function get(int $id)
    {
        $command = new MinerPoolsGetCommand($id);

        return $this->json(
            $this->getService->execute($command)
        );
    }
}