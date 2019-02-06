<?php

namespace App\AddHash\MinerPanel\Application\Controller;

use Swagger\Annotations as SWG;
use Symfony\Component\HttpFoundation\JsonResponse;
use App\AddHash\System\GlobalContext\Common\BaseServiceController;
use App\AddHash\MinerPanel\Domain\Dashboard\Services\DashboardListServiceInterface;

class DashboardController extends BaseServiceController
{
    private $listService;

    public function __construct(DashboardListServiceInterface $listService)
    {
        $this->listService = $listService;
    }

    /**
     * Get dashboard
     *
     * @SWG\Response(
     *     response=200,
     *     description="Return dashboard",
     *     @SWG\Schema(
     *          type="object",
     *          @SWG\Property(property="total", type="integer"),
     *          @SWG\Property(property="active", type="integer"),
     *          @SWG\Property(property="hashRate", type="number"),
     *          @SWG\Property(
     *              property="incomeBtc",
     *              type="object",
     *              @SWG\Property(property="day", type="number"),
     *              @SWG\Property(property="week", type="number"),
     *              @SWG\Property(property="month", type="number"),
     *          ),
     *          @SWG\Property(
     *              property="type",
     *              type="array",
     *              @SWG\Items(
     *                  type="object",
     *                  @SWG\Property(property="total", type="integer"),
     *                  @SWG\Property(property="active", type="integer"),
     *                  @SWG\Property(property="hashRate", type="number"),
     *                  @SWG\Property(property="typeId", type="integer"),
     *              ),
     *          ),
     *     ),
     * )
     *
     * @SWG\Response(
     *     response=400,
     *     description="Return validation errors"
     * )
     *
     * @return JsonResponse
     * @SWG\Tag(name="MinerPanel_Dashboard")
     */
    public function index()
    {
        return $this->json(
            $this->listService->execute()
        );
    }
}