<?php

namespace App\AddHash\AdminPanel\Application\Controller;

use Swagger\Annotations as SWG;
use Symfony\Component\HttpFoundation\JsonResponse;
use App\AddHash\System\GlobalContext\Common\BaseServiceController;
use App\AddHash\AdminPanel\Domain\Wallet\Services\Type\WalletTypeListServiceInterface;


class WalletTypeController extends BaseServiceController
{
    private $walletTypeListService;

	public function __construct(WalletTypeListServiceInterface $walletTypeListService)
	{
        $this->walletTypeListService = $walletTypeListService;
	}

    /**
     * Get wallet types
     *
     *
     * @SWG\Response(
     *     response=200,
     *     description="Returns wallet types",
     *     @SWG\Schema(
     *             type="array",
     *             @SWG\Items(
     *                 type="object",
     *                 @SWG\Property(property="id", type="integer"),
     *                 @SWG\Property(property="value", type="string"),
     *             )
     *     ),
     * )
     * @SWG\Response(
     *     response=400,
     *     description="Returns validation errors"
     * )
     *
     * @return JsonResponse
     * @SWG\Tag(name="Wallet type")
     */
	public function index()
	{
        return $this->json($this->walletTypeListService->execute());
	}
}