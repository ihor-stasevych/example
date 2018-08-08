<?php

namespace App\AddHash\AdminPanel\Application\Controller;

use App\AddHash\AdminPanel\Application\Command\Store\Order\StoreOrderAddProductCommand;
use App\AddHash\AdminPanel\Application\Command\Store\Order\StoreCheckoutCommand;
use App\AddHash\AdminPanel\Application\Command\Store\Order\StoreOrderCheckoutCommand;
use App\AddHash\AdminPanel\Application\Command\Store\Order\StoreOrderPrepareCheckoutCommand;
use App\AddHash\AdminPanel\Domain\Store\Order\Services\StoreOrderAddProductServiceInterface;
use App\AddHash\AdminPanel\Domain\Store\Order\Services\StoreOrderCheckoutServiceInterface;
use App\AddHash\AdminPanel\Domain\Store\Order\Services\StoreOrderCreateServiceInterface;
use App\AddHash\AdminPanel\Domain\Store\Order\Services\StoreOrderGetServiceInterface;
use App\AddHash\AdminPanel\Application\Command\Store\Order\StoreOrderCreateCommand;
use App\AddHash\AdminPanel\Domain\Store\Order\Services\StoreOrderPrepareCheckoutServiceInterface;
use App\AddHash\AdminPanel\Domain\Store\Order\StoreOrderTransformer;
use App\AddHash\AdminPanel\Domain\User\Services\Order\Miner\CreateUserOrderMinerServiceInterface;
use App\AddHash\AdminPanel\Infrastructure\Payment\Gateway\Stripe\PaymentGatewayStripe;
use App\AddHash\AdminPanel\Infrastructure\Services\Store\Order\StoreOrderRemoveItemService;
use App\AddHash\System\GlobalContext\Common\BaseServiceController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Swagger\Annotations as SWG;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

class StoreOrderController extends BaseServiceController
{
	private $storeOrderCreateService;
	private $storeOrderGetService;
	private $storeOrderCheckout;
	private $storeOrderAddProduct;
	private $storeOrderRemoveItem;
	private $orderMinerService;
	private $prepareCheckoutService;
	private $tokenStorage;

	public function __construct(
		StoreOrderCreateServiceInterface $storeOrderCreateService,
		StoreOrderGetServiceInterface $getService,
		StoreOrderCheckoutServiceInterface $checkoutService,
		StoreOrderAddProductServiceInterface $orderAddProductService,
		StoreOrderRemoveItemService $storeOrderRemoveItem,
		CreateUserOrderMinerServiceInterface $orderMinerService,
		StoreOrderPrepareCheckoutServiceInterface $prepareCheckoutService,
		TokenStorageInterface $tokenStorage
	)
	{
		$this->storeOrderCreateService = $storeOrderCreateService;
		$this->storeOrderGetService = $getService;
		$this->storeOrderCheckout = $checkoutService;
		$this->storeOrderAddProduct = $orderAddProductService;
		$this->storeOrderRemoveItem = $storeOrderRemoveItem;
		$this->orderMinerService = $orderMinerService;
		$this->prepareCheckoutService = $prepareCheckoutService;
		$this->tokenStorage = $tokenStorage;
	}


	/**
	 * Create order by product ids
	 *
	 * @SWG\Parameter(
	 *     name="products",
	 *     in="body",
	 *     type="array",
	 *     @SWG\Schema(
	 *         type="array",
	 *         @SWG\Items(type="integer")
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

		return $this->json([
			'price' => $order->getItemsPriceTotal(),
			'userEmail' => $order->getUser()->getEmail(),
			'apiKey' => PaymentGatewayStripe::getPublicKey()
		]);
	}

	/**
	 * Add product to existing order
	 *
	 * @SWG\Tag(name="Store Orders")
	 *
	 * @SWG\Parameter(
	 *     name="productId",
	 *     in="formData",
	 *     type="integer",
	 *     required=true,
	 *     description="id of the available product"
	 * )
	 *
	 * @SWG\Parameter(
	 *     name="orderId",
	 *     in="formData",
	 *     type="integer",
	 *     required=true,
	 *     description="id of existing order"
	 * )
	 *
	 * @param Request $request
	 *
	 * @SWG\Response(
	 *     response=200,
	 *     description="Returns true"
	 * )
	 *
	 * @return JsonResponse
	 */
	public function addProduct(Request $request)
	{
		//var_dump($request->request->all());
		$command = new StoreOrderAddProductCommand(
			$request->get('orderId'),
			$request->get('productId')
		);

		if (!$this->commandIsValid($command)) {
			return $this->json([
				'errors' => $this->getLastValidationErrors()
			], Response::HTTP_BAD_REQUEST);
		}

		try {
			$this->storeOrderAddProduct->execute($command);
		} catch (\Exception $e) {
			return $this->json([
				'errors' => $e->getMessage()
			], Response::HTTP_BAD_REQUEST);
		}

		return $this->json([]);
	}


	public function clear()
	{

	}


	/**
	 * Remove store order item from new order
	 *
	 * @param $id
	 * @return JsonResponse
	 *
	 * @SWG\Tag(name="Store Orders")
	 *
	 * @SWG\Parameter(
	 *     name="id",
	 *     in="path",
	 *     type="integer",
	 *     required=true,
	 *     description="id of the available item in order"
	 * )
	 *
	 * @SWG\Response(
	 *     response=200,
	 *     description="Returns ok"
	 *)
	 *
	 */
	public function removeItem($id)
	{
		try {
			$this->storeOrderRemoveItem->execute($id);
		} catch (\Exception $e) {
			return $this->json([
				'errors' => $e->getMessage()
			], Response::HTTP_BAD_REQUEST);
		}

		return $this->json([]);
	}

	/**
	 * Get pre checkout information by order
	 *
	 * @return JsonResponse
	 *
	 * @SWG\Tag(name="Store Orders")
	 *
	 * @SWG\Response(
	 *     response=200,
	 *     description="Returns information for checkout"
	 *)
	 *
	 */
	public function prepareCheckout()
	{
		try {
			$result = $this->prepareCheckoutService->execute();
		} catch(\Exception $e) {
			return $this->json([
				'errors' => $e->getMessage()
			]);
		}

		return $this->json($result);

	}

	/**
	 *
	 * @SWG\Tag(name="Store Orders")
	 *
	 * @SWG\Parameter(
	 *     name="stripeToken",
	 *     in="formData",
	 *     type="string",
	 *     required=true,
	 *     description="stripe front token"
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
		$command = new StoreOrderCheckoutCommand(
			$request->get('stripeToken')
		);

		if (!$this->commandIsValid($command)) {
			return $this->json([
				'errors' => $this->getLastValidationErrors()
			], Response::HTTP_BAD_REQUEST);
		}

		try {
			$order = $this->storeOrderCheckout->execute($command);
			$this->orderMinerService->execute($order);
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
		$result = [];
		$order = $this->storeOrderGetService->execute($this->tokenStorage->getToken()->getUser());

		if ($order) {
			$transformer = new StoreOrderTransformer();
			$result = $transformer->transform($order);
		}

		return $this->json(
			$result
		);
	}
}