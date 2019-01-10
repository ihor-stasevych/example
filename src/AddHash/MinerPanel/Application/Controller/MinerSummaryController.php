<?php

namespace App\AddHash\MinerPanel\Application\Controller;

use Swagger\Annotations as SWG;
use Symfony\Component\HttpFoundation\JsonResponse;
use App\AddHash\System\GlobalContext\Common\BaseServiceController;
use App\AddHash\MinerPanel\Application\Command\Miner\MinerSummary\MinerSummaryGetCommand;
use App\AddHash\MinerPanel\Domain\Miner\MinerSummary\Services\MinerSummaryGetServiceInterface;

class MinerSummaryController extends BaseServiceController
{
    private $getService;

    public function __construct(MinerSummaryGetServiceInterface $getService)
    {
        $this->getService = $getService;
    }

    /**
     * Get miner summary
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
     *     description="Return miner summary",
     *     @SWG\Schema(
     *          type="object",
     *          @SWG\Property(property="accepted", type="string"),
     *          @SWG\Property(property="rejected", type="string"),
     *          @SWG\Property(property="hashRate", type="string"),
     *          @SWG\Property(property="hashRateAverage", type="string"),
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
     * @SWG\Tag(name="MinerPanel_MinerSummary")
     */
    public function get(int $id)
    {
        $command = new MinerSummaryGetCommand($id);

        return $this->json(
            $this->getService->execute($command)
        );
    }
}