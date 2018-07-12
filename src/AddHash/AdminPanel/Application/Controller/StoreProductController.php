<?php

namespace App\AddHash\AdminPanel\Application\Controller;

use App\AddHash\AdminPanel\Domain\Store\Product\Services\StoreProductCreateServiceInterface;
use App\AddHash\AdminPanel\Domain\Store\Product\Services\StoreProductListServiceInterface;
use App\AddHash\AdminPanel\Infrastructure\Command\Store\Product\StoreProductCreateCommand;
use App\AddHash\System\GlobalContext\Common\BaseServiceController;
use League\Fractal\Manager;
use League\Fractal\Resource\Collection;
use Swagger\Annotations as SWG;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class StoreProductController extends BaseServiceController
{
	private $productListService;
	private $productCreateService;

	public function __construct(
		StoreProductListServiceInterface $productListService,
		StoreProductCreateServiceInterface $productCreateService
	)
	{
		$this->productListService = $productListService;
		$this->productCreateService = $productCreateService;
	}

	/**
	 * Get Store products list
	 *
	 * @SWG\Tag(name="Store products")
	 * @SWG\Response(
	 *     response=200,
	 *     description="Returns products list"
	 * )
	 * @return JsonResponse
	 */
	public function index()
	{
		//TODO:Implements Command and Search func

		$products = $this->productListService->execute();

		return $this->json($products);
	}

	/**
	 * Add new product
	 *
	 * @SWG\Parameter(
	 *     in="formData",
	 *     name="title",
	 *     type="string",
	 *     required=true,
	 *     description="Product title",
	 * )
	 *
	 * @SWG\Response(
	 *     response=200,
	 *     description="Returns new product"
	 * )
	 *
	 * @SWG\Tag(name="Admin Store")
	 * @param Request $request
	 * @return JsonResponse
	 */
	public function create(Request $request)
	{
		$command = new StoreProductCreateCommand($request);

		if (!$this->commandIsValid($command)) {
			return $this->json([
				'errors' => $this->getLastValidationErrors()
			], Response::HTTP_BAD_REQUEST);
		}

		$this->productCreateService->execute($command);

		return $this->json([]);
	}
}