<?php

namespace App\AddHash\AdminPanel\Application\Controller;

use App\AddHash\AdminPanel\Application\Command\Store\Order\StoreOrderCheckoutCommand;
use App\AddHash\AdminPanel\Domain\Store\Order\Command\StoreOrderCreateCommandInterface;
use App\AddHash\AdminPanel\Domain\Store\Order\Services\StoreOrderCheckoutServiceInterface;
use App\AddHash\AdminPanel\Domain\Store\Order\Services\StoreOrderCreateServiceInterface;
use App\AddHash\AdminPanel\Domain\Store\Order\Services\StoreOrderGetServiceInterface;
use App\AddHash\AdminPanel\Domain\Store\Order\StoreOrderException;
use App\AddHash\AdminPanel\Domain\User\User;
use App\AddHash\AdminPanel\Application\Command\Store\Order\StoreOrderCreateCommand;
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
	private $storeOrderCheckout;
	private $tokenStorage;

	public function __construct(
		StoreOrderCreateServiceInterface $storeOrderCreateService,
		StoreOrderGetServiceInterface $getService,
		StoreOrderCheckoutServiceInterface $checkoutService,
		TokenStorageInterface $tokenStorage
	)
	{
		$this->storeOrderCreateService = $storeOrderCreateService;
		$this->storeOrderGetService = $getService;
		$this->storeOrderCheckout = $checkoutService;
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

		try {
			$order = $this->storeOrderCreateService->execute($command);
		} catch (\Exception $e) {
			return $this->json([
				'errors' => $e->getMessage()
			], Response::HTTP_BAD_REQUEST);
		}

		return $this->json($order);
	}

	/**
	 *
	 * @SWG\Tag(name="Store Orders")
	 *
	 * @SWG\Parameter(
	 *     name="orderId",
	 *     in="query",
	 *     type="string",
	 *     required=true,
	 *     description="Id of new order"
	 * )
	 *
	 * @SWG\Response(
	 *     response=200,
	 *     description="Pay for products"
	 * )
	 *
	 * @param Request $request
	 * @return JsonResponse
	 */
	public function checkout(Request $request)
	{
		$command = new StoreOrderCheckoutCommand($request->get('orderId'));

		if (!$this->commandIsValid($command)) {
			return $this->json([
				'errors' => $this->getLastValidationErrors()
			], Response::HTTP_BAD_REQUEST);
		}

		try {
			$this->storeOrderCheckout->execute($command);

		} catch (\Exception $e) {
			return $this->json([
				'errors' => $e->getMessage()
			], Response::HTTP_BAD_REQUEST);
		}

		return $this->json([]);

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