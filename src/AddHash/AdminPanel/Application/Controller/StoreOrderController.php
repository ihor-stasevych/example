<?php

namespace App\AddHash\AdminPanel\Application\Controller;

use App\AddHash\AdminPanel\Domain\Store\Order\Command\StoreOrderCreateCommandInterface;
use App\AddHash\AdminPanel\Domain\Store\Order\Services\StoreOrderCreateServiceInterface;
use App\AddHash\AdminPanel\Domain\Store\Order\Services\StoreOrderGetServiceInterface;
use App\AddHash\AdminPanel\Domain\User\User;
use App\AddHash\AdminPanel\Infrastructure\Command\Store\Order\StoreOrderCreateCommand;
use App\AddHash\System\GlobalContext\Common\BaseServiceController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Swagger\Annotations as SWG;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Core\User\UserInterface;

class StoreOrderController extends BaseServiceController
{
	private $storeOrderCreateService;
	private $storeOrderGetService;
	private $tokenStorage;

	public function __construct(
		StoreOrderCreateServiceInterface $storeOrderCreateService,
		StoreOrderGetServiceInterface $getService,
		TokenStorageInterface $tokenStorage
	)
	{
		$this->storeOrderCreateService = $storeOrderCreateService;
		$this->storeOrderGetService = $getService;
		$this->tokenStorage = $tokenStorage;
	}


	/**
	 * Create order by product ids
	 *
	 * @SWG\Parameter(
	 *     name="products",
	 *     in="formData",
	 *     type="array",
	 *     @SWG\Items(
	 *         type="integer"
	 *     ),
	 *     required=true,
	 *     description="ids of the store products"
	 * )
	 *
	 *
	 * @SWG\Tag(name="Store Orders")
	 * @param Request $request
	 *
	 * @SWG\Response(
	 *     response=200,
	 *     description="Returns new order or existing"
	 * )
	 *
	 * @return JsonResponse
	 */
	public function create(Request $request)
	{
		$command = new StoreOrderCreateCommand(
			$request->get('products'),
			$this->tokenStorage->getToken()->getUser()
		);

		if (!$this->commandIsValid($command)) {
			return $this->json([
				'errors' => $this->getLastValidationErrors()
			], Response::HTTP_BAD_REQUEST);
		}

		$order = $this->storeOrderCreateService->execute($command);

		return $this->json($order);
	}

	/**
	 * Get available unpaid order by authorized user
	 *
	 * @SWG\Tag(name="Store Orders")
	 *
	 * @SWG\Response(
	 *     response=200,
	 *     description="Returns unpaid order"
	 * )
	 *
	 * @return JsonResponse
	 */
	public function get()
	{
		return $this->json(
			$this->storeOrderGetService->execute($this->tokenStorage->getToken()->getUser())
		);
	}
}