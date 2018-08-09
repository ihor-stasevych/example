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
use App\AddHash\AdminPanel\Domain\User\Services\MinerControlGetServiceInterface;
use App\AddHash\AdminPanel\Domain\User\Exceptions\MinerControlNoMainerException;

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