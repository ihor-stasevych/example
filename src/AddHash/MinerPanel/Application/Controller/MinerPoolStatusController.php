<?php

namespace App\AddHash\MinerPanel\Application\Controller;

use Swagger\Annotations as SWG;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use App\AddHash\System\GlobalContext\Common\BaseServiceController;
use App\AddHash\MinerPanel\Application\Command\Miner\MinerPoolStatus\MinerPoolStatusListCommand;
use App\AddHash\MinerPanel\Domain\Miner\MinerPoolStatus\Services\MinerPoolStatusListServiceInterface;
use App\AddHash\MinerPanel\Domain\Miner\MinerPoolStatus\Exceptions\MinerPoolStatusListInvalidCommandException;

class MinerPoolStatusController extends BaseServiceController
{
    private $listService;

    public function __construct(MinerPoolStatusListServiceInterface $listService)
    {
        $this->listService = $listService;
    }

    /**
     * Get miners pool status
     *
     * @SWG\Parameter(
     *     name="minersId[]",
     *     in="query",
     *     type="array",
     *     collectionFormat="multi",
     *     @SWG\Items(type="integer"),
     *     description="Miners ID",
     *     required=true,
     * )
     *
     * @SWG\Response(
     *     response=200,
     *     description="Return miners pool status",
     *     @SWG\Schema(
     *          type="array",
     *          @SWG\Items(
     *              type="object",
     *              @SWG\Property(property="id", type="integer"),
     *              @SWG\Property(property="status", type="integer"),
     *          ),
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
     * @throws MinerPoolStatusListInvalidCommandException
     * @SWG\Tag(name="MinerPanel_MinerPoolStatus")
     */
    public function index(Request $request)
    {
        $command = new MinerPoolStatusListCommand($request->get('minersId'));

        if (false === $this->commandIsValid($command)) {
            throw new MinerPoolStatusListInvalidCommandException('Invalid command');
        }

        return $this->json(
            $this->listService->execute($command)
        );
    }
}