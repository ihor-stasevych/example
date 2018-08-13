<?php

namespace App\AddHash\AdminPanel\Application\Controller\User;

use Swagger\Annotations as SWG;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use App\AddHash\AdminPanel\Domain\Miners\Miner; //TEST
use Symfony\Component\HttpFoundation\JsonResponse;
use App\AddHash\System\GlobalContext\Common\BaseServiceController;
use App\AddHash\AdminPanel\Infrastructure\Miners\Extender\MinerSocket; //TEST
use App\AddHash\AdminPanel\Infrastructure\Miners\Parsers\MinerSocketParser; //TEST
use App\AddHash\AdminPanel\Application\Command\User\MinerControlPoolGetCommand;
use App\AddHash\AdminPanel\Domain\User\Services\MinerControlGetServiceInterface;
use App\AddHash\AdminPanel\Domain\User\Exceptions\MinerControlNoMainerException;
use App\AddHash\AdminPanel\Domain\User\Services\MinerControlGetPoolServiceInterface;
use App\AddHash\AdminPanel\Domain\User\Services\MinerControlListPoolServiceInterface;
use App\AddHash\AdminPanel\Domain\User\Exceptions\MinerControlNoMainerExistException;

class MinerControlController extends BaseServiceController
{
    private $getService;

    private $listPoolService;

    private $getPoolService;

    public function __construct(
        MinerControlGetServiceInterface $getService,
        MinerControlListPoolServiceInterface $listPoolService,
        MinerControlGetPoolServiceInterface $getPoolService
    )
    {
        $this->getService = $getService;
        $this->listPoolService = $listPoolService;
        $this->getPoolService = $getPoolService;
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
        try {
            $data = $this->getService->execute();
        } catch (MinerControlNoMainerException $e) {
            return $this->json([
                'errors' => $e->getMessage(),
            ], Response::HTTP_BAD_REQUEST);
        }

        return $this->json($data);
    }

    /**
     * Get miners pools
     *
     * @SWG\Response(
     *     response=200,
     *     description="Returns the miners pools",
     *     @SWG\Schema(
     *             type="array",
     *             @SWG\Items(
     *                 type="object",
     *                 @SWG\Property(property="Status", type="string"),
     *                 @SWG\Property(property="Priority", type="integer")
     *             )
     *     ),
     * )
     *
     * @return JsonResponse
     * @SWG\Tag(name="User")
     */
    public function listPool()
    {
        try {
            $data = $this->listPoolService->execute();
        } catch (MinerControlNoMainerException $e) {
            return $this->json([
                'errors' => $e->getMessage(),
            ], Response::HTTP_BAD_REQUEST);
        }

        return $this->json($data);
    }

    public function getPool(int $id)
    {
        $command = new MinerControlPoolGetCommand($id);

        if (!$this->commandIsValid($command)) {
            return $this->json([
                'errors' => $this->getLastValidationErrors(),
            ], Response::HTTP_BAD_REQUEST);
        }

        try {
            $data = $this->getPoolService->execute($command);
        } catch (MinerControlNoMainerException | MinerControlNoMainerExistException $e) {
            return $this->json([
                'errors' => $e->getMessage(),
            ], Response::HTTP_BAD_REQUEST);
        }

        return $this->json($data);
    }

    /**
     * Command test cgminer
     *
     * @SWG\Parameter(
     *     in="formData",
     *     name="cmd",
     *     type="string",
     *     required=true,
     *     description="Command cgminer",
     * )
     *
     * @SWG\Response(
     *     response=200,
     *     description="Response api cgminer"
     * )
     *
     * @SWG\Tag(name="TEST")
     * @param Request $request
     * @return JsonResponse
     */
    public function testQueryMiner(Request $request)
    {
        $cmd = $request->get('cmd');
        $miner = new Miner('title', 'description', 'hash', '1', '1', '1', '1', '10.0.10.6', '1', '1', '4028');
        $parser = new MinerSocketParser();
        $socket = new MinerSocket($miner, $parser);

        return $this->json($socket->request($cmd));
    }
}