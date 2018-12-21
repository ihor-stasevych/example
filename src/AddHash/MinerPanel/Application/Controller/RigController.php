<?php

namespace App\AddHash\MinerPanel\Application\Controller;

use Swagger\Annotations as SWG;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use App\AddHash\MinerPanel\Application\Command\Rig\RigGetCommand;
use App\AddHash\System\GlobalContext\Common\BaseServiceController;
use App\AddHash\MinerPanel\Application\Command\Rig\RigListCommand;
use App\AddHash\MinerPanel\Application\Command\Rig\RigUpdateCommand;
use App\AddHash\MinerPanel\Application\Command\Rig\RigCreateCommand;
use App\AddHash\MinerPanel\Application\Command\Rig\RigDeleteCommand;
use App\AddHash\MinerPanel\Domain\Rig\Services\RigAllServiceInterface;
use App\AddHash\MinerPanel\Domain\Rig\Services\RigGetServiceInterface;
use App\AddHash\MinerPanel\Domain\Rig\Services\RigListServiceInterface;
use App\AddHash\MinerPanel\Domain\Rig\Services\RigDeleteServiceInterface;
use App\AddHash\MinerPanel\Domain\Rig\Services\RigUpdateServiceInterface;
use App\AddHash\MinerPanel\Domain\Rig\Services\RigCreateServiceInterface;
use App\AddHash\MinerPanel\Domain\Rig\Exceptions\RigListInvalidCommandException;
use App\AddHash\MinerPanel\Domain\Rig\Exceptions\RigUpdateInvalidCommandException;
use App\AddHash\MinerPanel\Domain\Rig\Exceptions\RigCreateInvalidCommandException;

class RigController extends BaseServiceController
{
    private $listService;

    private $getService;

    private $createService;

    private $updateService;

    private $deleteService;

    private $allService;

    public function __construct(
        RigListServiceInterface $listService,
        RigGetServiceInterface $getService,
        RigCreateServiceInterface $createService,
        RigUpdateServiceInterface $updateService,
        RigDeleteServiceInterface $deleteService,
        RigAllServiceInterface $allService
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
     * Get rigs with pagination
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
     *     description="Return rigs",
     *     @SWG\Schema(
     *          type="object",
     *          @SWG\Property(
     *              property="items",
     *              type="array",
     *              @SWG\Items(
     *                  type="object",
     *                  @SWG\Property(property="id", type="integer"),
     *                  @SWG\Property(property="title", type="string"),
     *                  @SWG\Property(property="worker", type="string"),
     *                  @SWG\Property(property="url", type="string"),
     *                  @SWG\Property(
     *                      property="miners",
     *                      type="array",
     *                      @SWG\Items(
     *                          type="object",
     *                          @SWG\Property(property="id", type="integer"),
     *                          @SWG\Property(property="title", type="string"),
     *                          @SWG\Property(property="ip", type="string"),
     *                          @SWG\Property(property="port", type="integer"),
     *                          @SWG\Property(property="hashRate", type="integer"),
     *                          @SWG\Property(property="type", type="string"),
     *                          @SWG\Property(property="algorithm", type="string"),
     *                      ),
     *                  ),
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
     * @throws RigListInvalidCommandException
     * @SWG\Tag(name="MinerPanel_Rig")
     */
    public function index(Request $request)
    {
        $command = new RigListCommand($request->get('page'));

        if (false === $this->commandIsValid($command)) {
            throw new RigListInvalidCommandException('Invalid page');
        }

        return $this->json(
            $this->listService->execute($command)
        );
    }

    /**
     * Get rig
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
     *     description="Return rig",
     *     @SWG\Schema(
     *          type="object",
     *          @SWG\Property(property="id", type="integer"),
     *          @SWG\Property(property="title", type="string"),
     *          @SWG\Property(property="worker", type="string"),
     *          @SWG\Property(property="url", type="string"),
     *          @SWG\Property(
     *              property="miners",
     *              type="array",
     *              @SWG\Items(
     *                  type="object",
     *                  @SWG\Property(property="id", type="integer"),
     *                  @SWG\Property(property="title", type="string"),
     *                  @SWG\Property(property="ip", type="string"),
     *                  @SWG\Property(property="port", type="integer"),
     *                  @SWG\Property(property="hashRate", type="integer"),
     *                  @SWG\Property(property="type", type="string"),
     *                  @SWG\Property(property="algorithm", type="string"),
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
     * @SWG\Tag(name="MinerPanel_Rig")
     */
    public function get(int $id)
    {
        $command = new RigGetCommand($id);

        return $this->json(
            $this->getService->execute($command)
        );
    }

    /**
     * Create rig
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
     *     name="worker",
     *     in="query",
     *     type="string",
     *     description="Worker",
     *     required=true,
     * )
     *
     * @SWG\Parameter(
     *     name="url",
     *     in="query",
     *     type="string",
     *     description="Url",
     *     required=true,
     * )
     *
     * @SWG\Parameter(
     *     name="password",
     *     in="query",
     *     type="string",
     *     description="Password",
     * )
     *
     * @SWG\Parameter(
     *     name="minersId[]",
     *     in="query",
     *     type="array",
     *     collectionFormat="multi",
     *     @SWG\Items(type="integer"),
     *     description="Miners ID",
     * )
     *
     * @SWG\Response(
     *     response=200,
     *     description="Return new rig",
     *     @SWG\Schema(
     *          type="object",
     *          @SWG\Property(property="id", type="integer"),
     *          @SWG\Property(property="title", type="string"),
     *          @SWG\Property(property="worker", type="string"),
     *          @SWG\Property(property="url", type="string"),
     *          @SWG\Property(
     *              property="miners",
     *              type="array",
     *              @SWG\Items(
     *                  type="object",
     *                  @SWG\Property(property="id", type="integer"),
     *                  @SWG\Property(property="title", type="string"),
     *                  @SWG\Property(property="ip", type="string"),
     *                  @SWG\Property(property="port", type="integer"),
     *                  @SWG\Property(property="hashRate", type="integer"),
     *                  @SWG\Property(property="type", type="string"),
     *                  @SWG\Property(property="algorithm", type="string"),
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
     * @SWG\Response(
     *     response=406,
     *     description="Return validation errors"
     * )
     *
     * @param Request $request
     * @return JsonResponse
     * @throws RigCreateInvalidCommandException
     * @SWG\Tag(name="MinerPanel_Rig")
     */
    public function create(Request $request)
    {
        $command = new RigCreateCommand(
            $request->get('title'),
            $request->get('worker'),
            $request->get('url'),
            $request->get('password'),
            $request->get('minersId')
        );

        if (false === $this->commandIsValid($command)) {
            throw new RigCreateInvalidCommandException(
                $this->getLastValidationErrors()
            );
        }

        return $this->json(
            $this->createService->execute($command)
        );
    }

    /**
     * Update rig
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
     *     name="worker",
     *     in="query",
     *     type="string",
     *     description="Worker",
     *     required=true,
     * )
     *
     * @SWG\Parameter(
     *     name="url",
     *     in="query",
     *     type="string",
     *     description="Url",
     *     required=true,
     * )
     *
     * @SWG\Parameter(
     *     name="password",
     *     in="query",
     *     type="string",
     *     description="Password",
     * )
     *
     * @SWG\Parameter(
     *     name="minersId[]",
     *     in="query",
     *     type="array",
     *     collectionFormat="multi",
     *     @SWG\Items(type="integer"),
     *     description="Miners ID",
     * )
     *
     * @SWG\Response(
     *     response=200,
     *     description="Return update rig",
     *     @SWG\Schema(
     *          type="object",
     *          @SWG\Property(property="id", type="integer"),
     *          @SWG\Property(property="title", type="string"),
     *          @SWG\Property(property="worker", type="string"),
     *          @SWG\Property(property="url", type="string"),
     *          @SWG\Property(
     *              property="miners",
     *              type="array",
     *              @SWG\Items(
     *                  type="object",
     *                  @SWG\Property(property="id", type="integer"),
     *                  @SWG\Property(property="title", type="string"),
     *                  @SWG\Property(property="ip", type="string"),
     *                  @SWG\Property(property="port", type="integer"),
     *                  @SWG\Property(property="hashRate", type="integer"),
     *                  @SWG\Property(property="type", type="string"),
     *                  @SWG\Property(property="algorithm", type="string"),
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
     * @SWG\Response(
     *     response=406,
     *     description="Return validation errors"
     * )
     *
     * @param int $id
     * @param Request $request
     * @return JsonResponse
     * @throws RigUpdateInvalidCommandException
     * @SWG\Tag(name="MinerPanel_Rig")
     */
    public function update(int $id, Request $request)
    {
        $command = new RigUpdateCommand(
            $id,
            $request->get('title'),
            $request->get('worker'),
            $request->get('url'),
            $request->get('password'),
            $request->get('minersId')
        );

        if (false === $this->commandIsValid($command)) {
            throw new RigUpdateInvalidCommandException(
                $this->getLastValidationErrors()
            );
        }

        return $this->json(
            $this->updateService->execute($command)
        );
    }

    /**
     * Delete rig
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
     *     description="Return success",
     * )
     *
     * @SWG\Response(
     *     response=400,
     *     description="Return validation errors"
     * )
     *
     * @param int $id
     * @return JsonResponse
     * @SWG\Tag(name="MinerPanel_Rig")
     */
    public function delete(int $id)
    {
        $command = new RigDeleteCommand($id);

        $this->deleteService->execute($command);

        return $this->json([]);
    }

    /**
     * Get rigs
     *
     * @SWG\Response(
     *     response=200,
     *     description="Return rigs",
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
     * @SWG\Tag(name="MinerPanel_Rig")
     */
    public function all()
    {
        return $this->json(
            $this->allService->execute()
        );
    }
}