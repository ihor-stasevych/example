<?php

namespace App\AddHash\MinerPanel\Application\Controller;

use Swagger\Annotations as SWG;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use App\AddHash\System\GlobalContext\Common\BaseServiceController;
use App\AddHash\MinerPanel\Application\Command\Miner\MinerGetCommand;
use App\AddHash\MinerPanel\Application\Command\Miner\MinerListCommand;
use App\AddHash\MinerPanel\Application\Command\Miner\MinerDeleteCommand;
use App\AddHash\MinerPanel\Application\Command\Miner\MinerCreateCommand;
use App\AddHash\MinerPanel\Application\Command\Miner\MinerUpdateCommand;
use App\AddHash\MinerPanel\Domain\Miner\Services\MinerGetServiceInterface;
use App\AddHash\MinerPanel\Domain\Miner\Services\MinerListServiceInterface;
use App\AddHash\MinerPanel\Domain\Miner\Services\MinerDeleteServiceInterface;
use App\AddHash\MinerPanel\Domain\Miner\Services\MinerCreateServiceInterface;
use App\AddHash\MinerPanel\Domain\Miner\Services\MinerUpdateServiceInterface;
use App\AddHash\MinerPanel\Domain\Miner\Exceptions\MinerListInvalidCommandException;
use App\AddHash\MinerPanel\Domain\Miner\Exceptions\MinerUpdateInvalidCommandException;
use App\AddHash\MinerPanel\Domain\Miner\Exceptions\MinerCreateInvalidCommandException;

class MinerController extends BaseServiceController
{
    private $listService;

    private $getService;

    private $createService;

    private $updateService;

    private $deleteService;

    public function __construct(
        MinerListServiceInterface $listService,
        MinerGetServiceInterface $getService,
        MinerCreateServiceInterface $createService,
        MinerUpdateServiceInterface $updateService,
        MinerDeleteServiceInterface $deleteService
    )
    {
        $this->listService = $listService;
        $this->getService = $getService;
        $this->createService = $createService;
        $this->updateService = $updateService;
        $this->deleteService = $deleteService;
    }

    /**
     * Get miners
     *
     * @SWG\Parameter(
     *     name="page",
     *     in="query",
     *     type="integer",
     *     description="Page",
     * )
     *
     * @SWG\Response(
     *     response=200,
     *     description="Returns miners",
     *     @SWG\Schema(
     *             type="array",
     *             @SWG\Items(
     *                 type="object",
     *                 @SWG\Property(property="id", type="integer"),
     *                 @SWG\Property(property="title", type="string"),
     *                 @SWG\Property(property="ip", type="string"),
     *                 @SWG\Property(property="port", type="integer"),
     *                 @SWG\Property(property="type", type="string"),
     *                 @SWG\Property(property="algorithm", type="string"),
     *             )
     *     ),
     * )
     * @param Request $request
     * @return JsonResponse
     * @throws MinerListInvalidCommandException
     * @SWG\Tag(name="MinerPanel")
     */
    public function index(Request $request)
    {
        $command = new MinerListCommand($request->get('page'));

        if (false === $this->commandIsValid($command)) {
            throw new MinerListInvalidCommandException('Invalid page');
        }

        return $this->json(
            $this->listService->execute($command)
        );
    }

    /**
     * Get miner
     *
     * @SWG\Response(
     *     response=200,
     *     description="Returns miner",
     *     @SWG\Schema(
     *              type="object",
     *              @SWG\Property(property="id", type="integer"),
     *              @SWG\Property(property="title", type="string"),
     *              @SWG\Property(property="ip", type="string"),
     *              @SWG\Property(property="port", type="integer"),
     *              @SWG\Property(property="type", type="string"),
     *              @SWG\Property(property="algorithm", type="string"),
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
        $command = new MinerGetCommand($id);

        return $this->json(
            $this->getService->execute($command)
        );
    }

    /**
     * Create miner
     *
     * @SWG\Parameter(
     *     name="title",
     *     in="query",
     *     type="string",
     *     description="Title",
     *     required=true,
     * )
     *
     * @SWG\Parameter(
     *     name="ip",
     *     in="query",
     *     type="string",
     *     description="IP",
     *     required=true,
     * )
     *
     * @SWG\Parameter(
     *     name="port",
     *     in="query",
     *     type="integer",
     *     description="Port",
     *     required=true,
     * )
     *
     * @SWG\Parameter(
     *     name="typeId",
     *     in="query",
     *     type="integer",
     *     description="Type Id",
     *     required=true,
     * )
     *
     * @SWG\Parameter(
     *     name="algorithmId",
     *     in="query",
     *     type="integer",
     *     description="Algorithm Id",
     *     required=true,
     * )
     *
     * @SWG\Response(
     *     response=200,
     *     description="Returns miner",
     *     @SWG\Schema(
     *              type="object",
     *              @SWG\Property(property="id", type="integer"),
     *              @SWG\Property(property="title", type="string"),
     *              @SWG\Property(property="ip", type="string"),
     *              @SWG\Property(property="port", type="integer"),
     *              @SWG\Property(property="type", type="string"),
     *              @SWG\Property(property="algorithm", type="string"),
     *              @SWG\Property(property="accepted", type="string"),
     *              @SWG\Property(property="rejected", type="string"),
     *              @SWG\Property(property="speed", type="string"),
     *              @SWG\Property(property="speedAverage", type="string"),
     *     )
     * )
     *
     * @SWG\Response(
     *     response=406,
     *     description="Returns validation errors"
     * )
     *
     * @SWG\Response(
     *     response=400,
     *     description="Returns validation errors"
     * )
     *
     * @param Request $request
     * @return JsonResponse
     * @throws MinerCreateInvalidCommandException
     * @SWG\Tag(name="MinerPanel")
     */
    public function create(Request $request)
    {
        $command = new MinerCreateCommand(
            $request->get('title'),
            $request->get('ip'),
            $request->get('port'),
            $request->get('typeId'),
            $request->get('algorithmId')
        );

        if (false === $this->commandIsValid($command)) {
            throw new MinerCreateInvalidCommandException(
                $this->getLastValidationErrors()
            );
        }

        return $this->json(
            $this->createService->execute($command)
        );
    }

    /**
     * Update miner
     *
     * @SWG\Parameter(
     *     name="title",
     *     in="query",
     *     type="string",
     *     description="Title",
     *     required=true,
     * )
     *
     * @SWG\Parameter(
     *     name="ip",
     *     in="query",
     *     type="string",
     *     description="IP",
     *     required=true,
     * )
     *
     * @SWG\Parameter(
     *     name="port",
     *     in="query",
     *     type="integer",
     *     description="Port",
     *     required=true,
     * )
     *
     * @SWG\Parameter(
     *     name="typeId",
     *     in="query",
     *     type="integer",
     *     description="Type Id",
     *     required=true,
     * )
     *
     * @SWG\Parameter(
     *     name="algorithmId",
     *     in="query",
     *     type="integer",
     *     description="Algorithm Id",
     *     required=true,
     * )
     *
     * @SWG\Response(
     *     response=200,
     *     description="Returns miner",
     *     @SWG\Schema(
     *              type="object",
     *              @SWG\Property(property="id", type="integer"),
     *              @SWG\Property(property="title", type="string"),
     *              @SWG\Property(property="ip", type="string"),
     *              @SWG\Property(property="port", type="integer"),
     *              @SWG\Property(property="type", type="string"),
     *              @SWG\Property(property="algorithm", type="string"),
     *              @SWG\Property(property="accepted", type="string"),
     *              @SWG\Property(property="rejected", type="string"),
     *              @SWG\Property(property="speed", type="string"),
     *              @SWG\Property(property="speedAverage", type="string"),
     *     )
     * )
     *
     * @SWG\Response(
     *     response=406,
     *     description="Returns validation errors"
     * )
     *
     * @SWG\Response(
     *     response=400,
     *     description="Returns validation errors"
     * )
     *
     * @param int $id
     * @param Request $request
     * @return JsonResponse
     * @throws MinerUpdateInvalidCommandException
     * @SWG\Tag(name="MinerPanel")
     */
    public function update(int $id, Request $request)
    {
        $command = new MinerUpdateCommand(
            $id,
            $request->get('title'),
            $request->get('ip'),
            $request->get('port'),
            $request->get('typeId'),
            $request->get('algorithmId')
        );

        if (false === $this->commandIsValid($command)) {
            throw new MinerUpdateInvalidCommandException(
                $this->getLastValidationErrors()
            );
        }

        return $this->json(
            $this->updateService->execute($command)
        );
    }

    /**
     * Delete miner
     *
     * @SWG\Response(
     *     response=200,
     *     description="Returns success"
     * )
     *
     * @SWG\Response(
     *     response=400,
     *     description="Returns validation errors"
     * )
     *
     * @param int $id
     * @return JsonResponse
     * @SWG\Tag(name="MinerPanel")
     */
    public function delete(int $id)
    {
        $command = new MinerDeleteCommand($id);

        $this->deleteService->execute($command);

        return $this->json([]);
    }
}