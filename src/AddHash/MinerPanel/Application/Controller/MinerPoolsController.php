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
     * Get pools
     *
     * @SWG\Response(
     *     response=200,
     *     description="Returns pools",
     *     @SWG\Schema(
     *              type="object",
     *              @SWG\Property(property="accepted", type="string"),
     *              @SWG\Property(property="rejected", type="string"),
     *              @SWG\Property(property="speed", type="string"),
     *              @SWG\Property(property="speedAverage", type="string"),
     *     )
     * )
     *
     * @param int $id
     * @return JsonResponse
     * @SWG\Tag(name="MinerPanel")
     */
    public function get(int $id)
    {
        $command = new MinerPoolsGetCommand($id);

        return $this->json(
            $this->getService->execute($command)
        );
    }
}