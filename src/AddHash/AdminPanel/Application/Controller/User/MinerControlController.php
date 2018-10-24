<?php

namespace App\AddHash\AdminPanel\Application\Controller\User;

use Swagger\Annotations as SWG;
use Symfony\Component\HttpFoundation\JsonResponse;
use App\AddHash\System\GlobalContext\Common\BaseServiceController;
use App\AddHash\AdminPanel\Domain\User\Services\MinerControlGetServiceInterface;

class MinerControlController extends BaseServiceController
{
    private $getService;

    public function __construct(MinerControlGetServiceInterface $getService)
    {
        $this->getService = $getService;
    }

    /**
     * Get miners information
     *
     * @SWG\Response(
     *     response=200,
     *     description="Returns the miners information",
     *     @SWG\Schema(
     *             type="array",
     *             @SWG\Items(
     *                 type="object",
     *                 @SWG\Property(property="Elapsed", type="integer"),
     *                 @SWG\Property(property="Found Block", type="string")
     *             )
     *     ),
     * )
     *
     * @return JsonResponse
     * @SWG\Tag(name="User")
     */
    public function get()
    {
        return $this->json($this->getService->execute());
    }
}