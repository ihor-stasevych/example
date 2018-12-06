<?php

namespace App\AddHash\MinerPanel\Application\Controller;

use Swagger\Annotations as SWG;
use Symfony\Component\HttpFoundation\JsonResponse;
use App\AddHash\System\GlobalContext\Common\BaseServiceController;
use App\AddHash\MinerPanel\Domain\Miner\MinerAlgorithm\Services\MinerAlgorithmListServiceInterface;

class MinerAlgorithmController extends BaseServiceController
{
    private $listService;

    public function __construct(MinerAlgorithmListServiceInterface $listService)
    {
        $this->listService = $listService;
    }

    /**
     * Get miners algorithm
     *
     * @SWG\Response(
     *     response=200,
     *     description="Returns miners algorithm",
     *     @SWG\Schema(
     *             type="array",
     *             @SWG\Items(
     *                 type="object",
     *                 @SWG\Property(property="id", type="integer"),
     *                 @SWG\Property(property="value", type="string"),
     *             )
     *     ),
     * )
     *
     * @return JsonResponse
     * @SWG\Tag(name="MinerPanel")
     */
    public function index()
    {
        return $this->json(
            $this->listService->execute()
        );
    }
}