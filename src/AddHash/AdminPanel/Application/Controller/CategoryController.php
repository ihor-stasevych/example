<?php

namespace App\AddHash\AdminPanel\Application\Controller;

use App\AddHash\AdminPanel\Domain\Store\Category\Services\ListServiceInterface;
use App\AddHash\System\GlobalContext\Common\BaseServiceController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Swagger\Annotations as SWG;

class CategoryController extends BaseServiceController
{
	protected $storeCategoryListService;

	public function __construct(ListServiceInterface $storeCategoryListService)
	{
		$this->storeCategoryListService = $storeCategoryListService;
	}

	/**
	 * List store categories
	 *
	 * Get store categories
	 *
	 *
	 * @SWG\Response(
	 *     response=200,
	 *     description="Returns the rewards of an user",
	 *     @SWG\Schema(
	 *              type="object",
	 *              @SWG\Property(property="id", type="integer"),
	 *              @SWG\Property(property="name", type="string")
	 *     )
	 * )
	 * @SWG\Parameter(
	 *     name="test",
	 *     in="query",
	 *     type="string",
	 *     description="test"
	 * )
	 * @SWG\Tag(name="Store Category")
	 */
	public function index(Request $request)
	{
		$result =  $this->storeCategoryListService->execute();

		return new JsonResponse($result, 200);
	}

	/**
	 * Get one category
	 * @param $id
	 *
	 * Test new one
	 *
	 * @SWG\Parameter(
	 *     name="id",
	 *     in="path",
	 *     type="string",
	 *     description="id of store category"
	 * )
	 *
	 * 	@SWG\Response(
	 *     response=200,
	 *     description="Returns the rewards of an user",
	 *     @SWG\Schema(
	 *              type="object",
	 *              @SWG\Property(property="id", type="integer"),
	 *              @SWG\Property(property="name", type="string")
	 *     )
	 *  )
	 *
	 * @SWG\Tag(name="Store Category")
	 * @return JsonResponse
	 *
	 */
	public function get($id)
	{
		return new JsonResponse($this->storeCategoryListService->getOne($id));
	}
}