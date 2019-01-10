<?php

namespace App\AddHash\MinerPanel\Application\Controller;

use Swagger\Annotations as SWG;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use App\AddHash\System\GlobalContext\Common\BaseServiceController;
use App\AddHash\MinerPanel\Application\Command\IpAddress\IpAddressCheckCommand;
use App\AddHash\MinerPanel\Domain\IpAddress\Services\IpAddressCheckServiceInterface;
use App\AddHash\MinerPanel\Domain\IpAddress\Exceptions\IpAddressCheckInvalidCommandException;

class IpAddressController extends BaseServiceController
{
    private $checkService;

    public function __construct(IpAddressCheckServiceInterface $checkService)
    {
        $this->checkService = $checkService;
    }

    /**
     * Check ip address
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
     *     default="4028"
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
     * @SWG\Response(
     *     response=406,
     *     description="Return validation errors"
     * )
     *
     * @param Request $request
     * @return JsonResponse
     * @throws IpAddressCheckInvalidCommandException
     * @SWG\Tag(name="MinerPanel_Validation")
     */
    public function check(Request $request)
    {
        $command = new IpAddressCheckCommand(
            $request->get('ip'),
            $request->get('port')
        );

        if (false === $this->commandIsValid($command)) {
            throw new IpAddressCheckInvalidCommandException(
                $this->getLastValidationErrors()
            );
        }

        $this->checkService->execute($command);

        return $this->json([]);
    }
}