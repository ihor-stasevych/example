<?php

namespace App\AddHash\MinerPanel\Application\Controller;

use Swagger\Annotations as SWG;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use App\AddHash\MinerPanel\Application\Command\Rig\RigGetCommand;
use App\AddHash\System\GlobalContext\Common\BaseServiceController;
use App\AddHash\MinerPanel\Application\Command\Rig\RigListCommand;
use App\AddHash\MinerPanel\Domain\Rig\Services\RigGetServiceInterface;
use App\AddHash\MinerPanel\Domain\Rig\Services\RigListServiceInterface;
use App\AddHash\MinerPanel\Domain\Rig\Exceptions\RigListInvalidCommandException;

class RigController extends BaseServiceController
{
    private $listService;

    private $getService;

    public function __construct(
        RigListServiceInterface $listService,
        RigGetServiceInterface $getService
    )
    {
        $this->listService = $listService;
        $this->getService = $getService;
    }

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

    public function get(int $id)
    {
        $command = new RigGetCommand($id);

        return $this->json(
            $this->getService->execute($command)
        );
    }

    public function create(Request $request)
    {

    }

    public function update(int $id, Request $request)
    {

    }

    public function delete(int $id)
    {

    }
}