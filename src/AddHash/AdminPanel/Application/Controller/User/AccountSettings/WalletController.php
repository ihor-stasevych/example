<?php

namespace App\AddHash\AdminPanel\Application\Controller\User\AccountSettings;

use Swagger\Annotations as SWG;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use App\AddHash\System\GlobalContext\Common\BaseServiceController;
use App\AddHash\AdminPanel\Application\Command\User\AccountSettings\WalletCreateCommand;
use App\AddHash\AdminPanel\Application\Command\User\AccountSettings\WalletUpdateCommand;
use App\AddHash\AdminPanel\Domain\User\Services\AccountSettings\WalletGetServiceInterface;
use App\AddHash\AdminPanel\Domain\User\Services\AccountSettings\WalletUpdateServiceInterface;
use App\AddHash\AdminPanel\Domain\User\Services\AccountSettings\WalletCreateServiceInterface;

class WalletController extends BaseServiceController
{
    private $createService;

    private $updateService;

    private $getService;

    public function __construct(
        WalletCreateServiceInterface $createService,
        WalletUpdateServiceInterface $updateService,
        WalletGetServiceInterface $getService
    )
    {
        $this->createService = $createService;
        $this->updateService = $updateService;
        $this->getService = $getService;
    }

    /**
     * Get user wallets by authorized user
     *
     * @SWG\Response(
     *     response=200,
     *     description="Returns the user wallets",
     *     @SWG\Schema(
     *             type="array",
     *             @SWG\Items(
     *                 type="object",
     *                 @SWG\Property(property="id", type="integer"),
     *                 @SWG\Property(property="typeId", type="integer"),
     *                 @SWG\Property(property="value", type="string"),
     *             )
     *     ),
     * )
     *
     * @return JsonResponse
     * @SWG\Tag(name="User")
     */
    public function get()
    {
        return $this->json($this->getService->execute());
    }

    /**
     * Update user wallets by authorized user
     *
     * @SWG\Parameter(
     *     name="wallets",
     *     in="body",
     *     description="id - user wallet ID, value - new value wallet, typeId - new type ID",
     *     required=true,
     *     @SWG\Schema(
     *         type="array",
     *         @SWG\Items(
     *            type="object",
     *            @SWG\Property(property="id", type="integer"),
     *            @SWG\Property(property="typeId", type="integer"),
     *            @SWG\Property(property="value", type="string"),
     *         )
     *     )
     * )
     *
     * @SWG\Response(
     *     response=200,
     *     description="Returns the update user wallets",
     *     @SWG\Schema(
     *             type="array",
     *             @SWG\Items(
     *                 type="object",
     *                 @SWG\Property(property="id", type="integer"),
     *                 @SWG\Property(property="typeId", type="integer"),
     *                 @SWG\Property(property="value", type="string"),
     *             )
     *     ),
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
     * @SWG\Tag(name="User")
     */
    public function update(Request $request)
	{
        $command = new WalletUpdateCommand($request->get('wallets'));

        if (false === $this->commandIsValid($command)) {
            return $this->json([
                'errors' => $this->getLastValidationErrors(),
            ], Response::HTTP_NOT_ACCEPTABLE);
        }

        return $this->json($this->updateService->execute($command));
	}

    /**
     * Create user wallet by authorized user
     *
     * @SWG\Parameter(
     *     name="value",
     *     in="query",
     *     type="string",
     *     description="Value wallet",
     *     required=true,
     * )
     *
     * @SWG\Parameter(
     *     name="typeId",
     *     in="query",
     *     type="integer",
     *     description="Wallet type ID",
     *     required=true,
     * )
     *
     * @SWG\Response(
     *     response=200,
     *     description="Returns the create user wallets",
     *     @SWG\Schema(
     *              type="object",
     *              @SWG\Property(property="id", type="integer"),
     *              @SWG\Property(property="typeId", type="integer"),
     *              @SWG\Property(property="value", type="string"),
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
     * @SWG\Tag(name="User")
     */
	public function create(Request $request)
    {
        $command = new WalletCreateCommand(
            $request->get('value'),
            $request->get('typeId')
        );

        if (false === $this->commandIsValid($command)) {
            return $this->json([
                'errors' => $this->getLastValidationErrors(),
            ], Response::HTTP_NOT_ACCEPTABLE);
        }

        return $this->json($this->createService->execute($command));
    }
}