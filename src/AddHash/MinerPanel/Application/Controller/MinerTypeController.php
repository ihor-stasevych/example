<?php

namespace App\AddHash\MinerPanel\Application\Controller;

use Swagger\Annotations as SWG;
use Symfony\Component\HttpFoundation\JsonResponse;
use App\AddHash\System\GlobalContext\Common\BaseServiceController;
use App\AddHash\MinerPanel\Domain\Miner\MinerType\Services\MinerTypeListServiceInterface;

class MinerTypeController extends BaseServiceController
{
    private $listService;

    public function __construct(MinerTypeListServiceInterface $listService)
    {
        $this->listService = $listService;
    }

    /**
     * Get miners type
     *
     * @SWG\Response(
     *     response=200,
     *     description="Returns miners type",
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