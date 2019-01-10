<?php

namespace App\AddHash\MinerPanel\Application\Controller;

use Swagger\Annotations as SWG;
use Symfony\Component\HttpFoundation\JsonResponse;
use App\AddHash\System\GlobalContext\Common\BaseServiceController;
use App\AddHash\MinerPanel\Domain\Miner\MinerType\Services\MinerTypeAllServiceInterface;

class MinerTypeController extends BaseServiceController
{
    private $allService;

    public function __construct(MinerTypeAllServiceInterface $allService)
    {
        $this->allService = $allService;
    }

    /**
     * Get miners type
     *
     * @SWG\Response(
     *     response=200,
     *     description="Return miners type",
     *     @SWG\Schema(
     *          type="array",
     *          @SWG\Items(
     *              type="object",
     *              @SWG\Property(property="id", type="integer"),
     *              @SWG\Property(property="value", type="string"),
     *          )
     *     ),
     * )
     *
     * @SWG\Response(
     *     response=400,
     *     description="Return validation errors"
     * )
     *
     * @return JsonResponse
     * @SWG\Tag(name="MinerPanel")
     */
    public function all()
    {
        return $this->json(
            $this->allService->execute()
        );
    }
}