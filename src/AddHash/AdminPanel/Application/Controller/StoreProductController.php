<?php

namespace App\AddHash\AdminPanel\Application\Controller;

use Swagger\Annotations as SWG;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use App\AddHash\System\GlobalContext\Common\BaseServiceController;
use App\AddHash\AdminPanel\Domain\Store\Product\Exceptions\Vote\VoteException;
use App\AddHash\AdminPanel\Application\Command\Store\Product\StoreProductGetCommand;
use App\AddHash\AdminPanel\Application\Command\Store\Product\StoreProductListCommand;
use App\AddHash\AdminPanel\Application\Command\Store\Product\StoreProductCreateCommand;
use App\AddHash\AdminPanel\Domain\Store\Product\Services\StoreProductGetServiceInterface;
use App\AddHash\AdminPanel\Domain\Store\Product\Services\StoreProductListServiceInterface;
use App\AddHash\AdminPanel\Application\Command\Store\Product\StoreProductVoteCreateCommand;
use App\AddHash\AdminPanel\Domain\Store\Product\Services\StoreProductCreateServiceInterface;
use App\AddHash\AdminPanel\Domain\Store\Product\Services\StoreProductVoteCreateServiceInterface;

class StoreProductController extends BaseServiceController
{
	private $productListService;

    private $productGetService;

	private $productCreateService;

	private $productVoteCreateService;

	public function __construct(
		StoreProductListServiceInterface $productListService,
        StoreProductGetServiceInterface $productGetService,
		StoreProductCreateServiceInterface $productCreateService,
        StoreProductVoteCreateServiceInterface $productVoteCreateService
	)
	{
		$this->productListService = $productListService;
        $this->productVoteCreateService = $productVoteCreateService;
		$this->productCreateService = $productCreateService;
		$this->productGetService = $productGetService;
	}

	/**
	 * Get Store products list
     *
     * @SWG\Parameter(
     *     in="formData",
     *     name="sort",
     *     type="string",
     *     required=false,
     *     description="Sort product (price or createdAt)",
     * )
	 *
     * @SWG\Parameter(
     *     in="formData",
     *     name="order",
     *     type="string",
     *     required=false,
     *     description="Order product (asc or desc)",
     * )
     *
     *
	 * @SWG\Tag(name="Store products")
	 * @SWG\Response(
	 *     response=200,
	 *     description="Returns products list"
	 * )
     * @param Request $request
	 * @return JsonResponse
	 */
	public function index(Request $request)
	{
		$command = new StoreProductListCommand(
            $request->get('sort'),
            $request->get('order')
        );

		return $this->json($this->productListService->execute($command));
	}

	/**
	 * @param $id
	 * @SWG\Tag(name="Store products")
	 * @SWG\Response(
	 *     response=200,
	 *     description="Returns products list"
	 * )
	 *
	 * @return JsonResponse
	 */
	public function get($id)
	{
		$command = new StoreProductGetCommand($id);

        if (!$this->commandIsValid($command)) {
            return $this->json([
                'errors' => $this->getLastValidationErrors()
            ], Response::HTTP_BAD_REQUEST);
        }

		return $this->json($this->productGetService->execute($command));
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

    /**
     * Add vote by authorized user
     *
     * @SWG\Parameter(
     *     in="formData",
     *     name="productId",
     *     type="integer",
     *     required=true,
     *     description="Product ID",
     * )
     *
     * @SWG\Parameter(
     *     in="formData",
     *     name="value",
     *     type="integer",
     *     required=true,
     *     description="Rating from 1 to 5",
     * )
     *
     * @SWG\Response(
     *     response=200,
     *     description="Returns success"
     * )
     *
     * @SWG\Response(
     *     response=400,
     *     description="Returns validation errors"
     * )
     *
     * @SWG\Tag(name="Store products")
     * @param Request $request
     * @return JsonResponse
     */
	public function createVote(Request $request)
    {
        $command = new StoreProductVoteCreateCommand($request->get('productId'), $request->get('value'));

        if (!$this->commandIsValid($command)) {
            return $this->json([
                'errors' => $this->getLastValidationErrors()
            ], Response::HTTP_BAD_REQUEST);
        }

        try {
            $this->productVoteCreateService->execute($command);
        } catch (VoteException $e) {
            return $this->json([
                'errors' => $e->getMessage(),
            ], Response::HTTP_BAD_REQUEST);
        }

        return $this->json([]);
    }
}