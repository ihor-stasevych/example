<?php

namespace App\AddHash\AdminPanel\Application\Controller;

use Swagger\Annotations as SWG;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use App\AddHash\System\GlobalContext\Common\BaseServiceController;
use App\AddHash\AdminPanel\Application\Command\Store\Order\StoreOrderCreateCommand;
use App\AddHash\AdminPanel\Domain\Store\Order\Services\StoreOrderGetServiceInterface;
use App\AddHash\AdminPanel\Application\Command\Store\Order\StoreOrderCheckoutCommand;
use App\AddHash\AdminPanel\Application\Command\Store\Order\StoreOrderAddProductCommand;
use App\AddHash\AdminPanel\Domain\Store\Order\Services\StoreOrderCreateServiceInterface;
use App\AddHash\AdminPanel\Domain\Store\Order\Services\StoreOrderCheckoutServiceInterface;
use App\AddHash\AdminPanel\Infrastructure\Services\Store\Order\StoreOrderRemoveItemService;
use App\AddHash\AdminPanel\Domain\Store\Order\Services\StoreOrderAddProductServiceInterface;
use App\AddHash\AdminPanel\Domain\Store\Order\Services\StoreOrderPrepareCheckoutServiceInterface;
use App\AddHash\AdminPanel\Domain\User\Services\Order\Miner\CreateUserOrderMinerServiceInterface;

class StoreOrderController extends BaseServiceController
{
	private $storeOrderCreateService;

	private $storeOrderGetService;

	private $storeOrderCheckout;

	private $storeOrderAddProduct;

	private $storeOrderRemoveItem;

	private $orderMinerService;

	private $prepareCheckoutService;

	public function __construct(
		StoreOrderCreateServiceInterface $storeOrderCreateService,
		StoreOrderGetServiceInterface $getService,
		StoreOrderCheckoutServiceInterface $checkoutService,
		StoreOrderAddProductServiceInterface $orderAddProductService,
		StoreOrderRemoveItemService $storeOrderRemoveItem,
		CreateUserOrderMinerServiceInterface $orderMinerService,
		StoreOrderPrepareCheckoutServiceInterface $prepareCheckoutService
	)
	{
		$this->storeOrderCreateService = $storeOrderCreateService;
		$this->storeOrderGetService = $getService;
		$this->storeOrderCheckout = $checkoutService;
		$this->storeOrderAddProduct = $orderAddProductService;
		$this->storeOrderRemoveItem = $storeOrderRemoveItem;
		$this->orderMinerService = $orderMinerService;
		$this->prepareCheckoutService = $prepareCheckoutService;
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
     *     response=400,
     *     description="Return validation errors"
     * )
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
		$command = new StoreOrderCreateCommand($request->get('products'));

		if (false === $this->commandIsValid($command)) {
			return $this->json([
				'errors' => $this->getLastValidationErrors()
			], Response::HTTP_BAD_REQUEST);
		}

		return $this->json($this->storeOrderCreateService->execute($command));
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
		$command = new StoreOrderAddProductCommand(
			$request->get('orderId'),
			$request->get('productId')
		);

		if (false === $this->commandIsValid($command)) {
			return $this->json([
				'errors' => $this->getLastValidationErrors()
			], Response::HTTP_BAD_REQUEST);
		}

        $this->storeOrderAddProduct->execute($command);

		return $this->json([]);
	}

    /**
     * Remove store order item from new order
     *
     * @param int $id
     * @return JsonResponse
     *
     * @throws \App\AddHash\AdminPanel\Domain\Store\Order\StoreOrderException
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
     */
	public function removeItem(int $id)
	{
        $this->storeOrderRemoveItem->execute($id);

		return $this->json([]);
	}

	/**
	 * Get pre checkout information by order
	 *
     * @SWG\Response(
     *     response=400,
     *     description="Return validation errors"
     * )
     *
	 * @SWG\Response(
	 *     response=200,
	 *     description="Returns information for checkout"
	 *)
     *
     * @return JsonResponse
	 * @SWG\Tag(name="Store Orders")
	 */
	public function prepareCheckout()
	{
		return $this->json($this->prepareCheckoutService->execute());
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
     *     response=400,
     *     description="Return validation errors"
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
		$command = new StoreOrderCheckoutCommand($request->get('stripeToken'));

		if (false === $this->commandIsValid($command)) {
			return $this->json([
				'errors' => $this->getLastValidationErrors()
			], Response::HTTP_BAD_REQUEST);
		}

        $order = $this->storeOrderCheckout->execute($command);
        $this->orderMinerService->execute($order);

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
		return $this->json($this->storeOrderGetService->execute());
	}
}