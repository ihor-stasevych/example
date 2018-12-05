<?php

namespace App\AddHash\MinerPanel\Application\Controller;

use Swagger\Annotations as SWG;
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
     * @SWG\Response(
     *     response=200,
     *     description="Returns success"
     * )
     *
     * @SWG\Response(
     *     response=406,
     *     description="Returns validation errors"
     * )
     *
     * @param string $ip
     * @return JsonResponse
     * @throws IpAddressCheckInvalidCommandException
     * @SWG\Tag(name="MinerPanel")
     */
    public function check(string $ip)
    {
        $command = new IpAddressCheckCommand($ip);

        if (false === $this->commandIsValid($command)) {
            throw new IpAddressCheckInvalidCommandException(
                $this->getLastValidationErrors()
            );
        }

        $this->checkService->execute($command);

        return $this->json([]);
    }
}