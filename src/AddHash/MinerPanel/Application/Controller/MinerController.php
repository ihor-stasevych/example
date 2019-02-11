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
use App\AddHash\MinerPanel\Domain\Miner\Services\MinerAllServiceInterface;
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

    private $allService;

    public function __construct(
        MinerListServiceInterface $listService,
        MinerGetServiceInterface $getService,
        MinerCreateServiceInterface $createService,
        MinerUpdateServiceInterface $updateService,
        MinerDeleteServiceInterface $deleteService,
        MinerAllServiceInterface $allService
    )
    {
        $this->listService = $listService;
        $this->getService = $getService;
        $this->createService = $createService;
        $this->updateService = $updateService;
        $this->deleteService = $deleteService;
        $this->allService = $allService;
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
     *     description="Return miners",
     *     @SWG\Schema(
     *          type="object",
     *          @SWG\Property(
     *              property="items",
     *              type="array",
     *              @SWG\Items(
     *                  type="object",
     *                  @SWG\Property(property="id", type="integer"),
     *                  @SWG\Property(property="title", type="string"),
     *                  @SWG\Property(property="ip", type="string"),
     *                  @SWG\Property(property="port", type="integer"),
     *                  @SWG\Property(property="hashRate", type="number"),
     *                  @SWG\Property(property="type", type="string"),
     *                  @SWG\Property(property="algorithm", type="string"),
     *                  @SWG\Property(property="rig", type="string"),
     *              ),
     *          ),
     *          @SWG\Property(property="totalItems", type="integer"),
     *          @SWG\Property(property="totalPages", type="integer"),
     *          @SWG\Property(property="page", type="integer"),
     *          @SWG\Property(property="limit", type="integer"),
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
     * @throws MinerListInvalidCommandException
     * @SWG\Tag(name="MinerPanel_Miner")
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
     *     description="Return miner",
     *     @SWG\Schema(
     *          type="object",
     *          @SWG\Property(property="id", type="integer"),
     *          @SWG\Property(property="title", type="string"),
     *          @SWG\Property(property="ip", type="string"),
     *          @SWG\Property(property="port", type="integer"),
     *          @SWG\Property(property="hashRate", type="number"),
     *          @SWG\Property(property="type", type="string"),
     *          @SWG\Property(property="algorithm", type="string"),
     *          @SWG\Property(property="rig", type="string"),
     *          @SWG\Property(
     *              property="summary",
     *              type="object",
     *              @SWG\Property(property="status", type="string"),
     *              @SWG\Property(property="accepted", type="number"),
     *              @SWG\Property(property="rejected", type="number"),
     *              @SWG\Property(property="hashRate", type="number"),
     *              @SWG\Property(property="hashRateAverage", type="number"),
     *          ),
     *          @SWG\Property(
     *              property="pools",
     *              type="array",
     *              @SWG\Items(
     *                  type="object",
     *                  @SWG\Property(property="url", type="string"),
     *                  @SWG\Property(property="user", type="string"),
     *                  @SWG\Property(property="status", type="integer"),
     *              ),
     *          ),
     *          @SWG\Property(
     *              property="coins",
     *              type="array",
     *              @SWG\Items(
     *                  type="object",
     *                  @SWG\Property(property="name", type="string"),
     *                  @SWG\Property(property="tag", type="string"),
     *                  @SWG\Property(property="income", type="string"),
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
     * @param int $id
     * @return JsonResponse
     * @SWG\Tag(name="MinerPanel_Miner")
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
     * )
     *
     * @SWG\Parameter(
     *     name="sshPort",
     *     in="query",
     *     type="integer",
     *     description="Port SSH",
     * )
     *
     * @SWG\Parameter(
     *     name="sshLogin",
     *     in="query",
     *     type="string",
     *     description="Login SSH",
     * )
     *
     * @SWG\Parameter(
     *     name="sshPassword",
     *     in="query",
     *     type="string",
     *     description="Password SSH",
     * )
     *
     * @SWG\Parameter(
     *     name="typeId",
     *     in="query",
     *     type="integer",
     *     description="Type ID",
     *     required=true,
     * )
     *
     * @SWG\Parameter(
     *     name="algorithmId",
     *     in="query",
     *     type="integer",
     *     description="Algorithm ID",
     *     required=true,
     * )
     *
     * @SWG\Parameter(
     *     name="rigId",
     *     in="query",
     *     type="integer",
     *     description="Rig ID",
     * )
     *
     * @SWG\Response(
     *     response=200,
     *     description="Return miner",
     *     @SWG\Schema(
     *          type="object",
     *          @SWG\Property(property="id", type="integer"),
     *          @SWG\Property(property="title", type="string"),
     *          @SWG\Property(property="ip", type="string"),
     *          @SWG\Property(property="port", type="integer"),
     *          @SWG\Property(property="sshPort", type="integer"),
     *          @SWG\Property(property="sshLogin", type="string"),
     *          @SWG\Property(property="hashRate", type="number"),
     *          @SWG\Property(property="type", type="string"),
     *          @SWG\Property(property="algorithm", type="string"),
     *          @SWG\Property(property="rig", type="string"),
     *          @SWG\Property(
     *              property="summary",
     *              type="object",
     *              @SWG\Property(property="status", type="string"),
     *              @SWG\Property(property="accepted", type="number"),
     *              @SWG\Property(property="rejected", type="number"),
     *              @SWG\Property(property="hashRate", type="number"),
     *              @SWG\Property(property="hashRateAverage", type="number"),
     *          ),
     *          @SWG\Property(
     *              property="pools",
     *              type="array",
     *              @SWG\Items(
     *                  type="object",
     *                  @SWG\Property(property="url", type="string"),
     *                  @SWG\Property(property="user", type="string"),
     *                  @SWG\Property(property="status", type="integer"),
     *              ),
     *          ),
     *          @SWG\Property(
     *              property="coins",
     *              type="array",
     *              @SWG\Items(
     *                  type="object",
     *                  @SWG\Property(property="name", type="string"),
     *                  @SWG\Property(property="tag", type="string"),
     *                  @SWG\Property(property="income", type="string"),
     *              ),
     *          ),
     *     ),
     * )
     *
     * @SWG\Response(
     *     response=406,
     *     description="Return validation errors"
     * )
     *
     * @SWG\Response(
     *     response=400,
     *     description="Return validation errors"
     * )
     *
     * @param Request $request
     * @return JsonResponse
     * @throws MinerCreateInvalidCommandException
     * @SWG\Tag(name="MinerPanel_Miner")
     */
    public function create(Request $request)
    {
        $command = new MinerCreateCommand(
            $request->get('title'),
            $request->get('ip'),
            $request->get('port'),
            $request->get('sshPort'),
            $request->get('sshLogin'),
            $request->get('sshPassword'),
            $request->get('typeId'),
            $request->get('algorithmId'),
            $request->get('rigId')
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
     *     name="id",
     *     in="path",
     *     type="integer",
     *     description="ID",
     *     required=true,
     * )
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
     *     name="sshPort",
     *     in="query",
     *     type="integer",
     *     description="Port SSH",
     * )
     *
     * @SWG\Parameter(
     *     name="sshLogin",
     *     in="query",
     *     type="string",
     *     description="Login SSH",
     * )
     *
     * @SWG\Parameter(
     *     name="sshPassword",
     *     in="query",
     *     type="string",
     *     description="Password SSH",
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
     * @SWG\Parameter(
     *     name="rigId",
     *     in="query",
     *     type="integer",
     *     description="Rig ID",
     * )
     *
     * @SWG\Response(
     *     response=200,
     *     description="Return update miner",
     *     @SWG\Schema(
     *          type="object",
     *          @SWG\Property(property="id", type="integer"),
     *          @SWG\Property(property="title", type="string"),
     *          @SWG\Property(property="ip", type="string"),
     *          @SWG\Property(property="port", type="integer"),
     *          @SWG\Property(property="sshPort", type="integer"),
     *          @SWG\Property(property="sshLogin", type="string"),
     *          @SWG\Property(property="hashRate", type="number"),
     *          @SWG\Property(property="type", type="string"),
     *          @SWG\Property(property="algorithm", type="string"),
     *          @SWG\Property(property="rig", type="string"),
     *          @SWG\Property(
     *              property="summary",
     *              type="object",
     *              @SWG\Property(property="status", type="string"),
     *              @SWG\Property(property="accepted", type="number"),
     *              @SWG\Property(property="rejected", type="number"),
     *              @SWG\Property(property="hashRate", type="number"),
     *              @SWG\Property(property="hashRateAverage", type="number"),
     *          ),
     *          @SWG\Property(
     *              property="pools",
     *              type="array",
     *              @SWG\Items(
     *                  type="object",
     *                  @SWG\Property(property="url", type="string"),
     *                  @SWG\Property(property="user", type="string"),
     *                  @SWG\Property(property="status", type="integer"),
     *              ),
     *          ),
     *          @SWG\Property(
     *              property="coins",
     *              type="array",
     *              @SWG\Items(
     *                  type="object",
     *                  @SWG\Property(property="name", type="string"),
     *                  @SWG\Property(property="tag", type="string"),
     *                  @SWG\Property(property="income", type="string"),
     *              ),
     *          ),
     *     ),
     * )
     *
     * @SWG\Response(
     *     response=406,
     *     description="Return validation errors"
     * )
     *
     * @SWG\Response(
     *     response=400,
     *     description="Return validation errors"
     * )
     *
     * @param int $id
     * @param Request $request
     * @return JsonResponse
     * @throws MinerUpdateInvalidCommandException
     * @SWG\Tag(name="MinerPanel_Miner")
     */
    public function update(int $id, Request $request)
    {
        $command = new MinerUpdateCommand(
            $id,
            $request->get('title'),
            $request->get('ip'),
            $request->get('port'),
            $request->get('sshPort'),
            $request->get('sshLogin'),
            $request->get('sshPassword'),
            $request->get('typeId'),
            $request->get('algorithmId'),
            $request->get('rigId')
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
     *     description="Return success"
     * )
     *
     * @SWG\Response(
     *     response=400,
     *     description="Return validation errors"
     * )
     *
     * @param int $id
     * @return JsonResponse
     * @SWG\Tag(name="MinerPanel_Miner")
     */
    public function delete(int $id)
    {
        $command = new MinerDeleteCommand($id);

        $this->deleteService->execute($command);

        return $this->json([]);
    }

    /**
     * Get miners
     *
     * @SWG\Response(
     *     response=200,
     *     description="Return miners",
     *     @SWG\Schema(
     *          type="array",
     *          @SWG\Items(
     *              type="object",
     *              @SWG\Property(property="id", type="integer"),
     *              @SWG\Property(property="title", type="string"),
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
     * @SWG\Tag(name="MinerPanel_Miner")
     */
    public function all()
    {
        return $this->json(
            $this->allService->execute()
        );
    }
}