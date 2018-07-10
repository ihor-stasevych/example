<?php

namespace App\AddHash\AdminPanel\Application\Controller;

use App\AddHash\AdminPanel\Domain\Store\Category\Services\CreateServiceInterface;
use App\AddHash\AdminPanel\Domain\Store\Category\Services\ListServiceInterface;
use App\AddHash\AdminPanel\Infrastructure\Command\Store\Category\StoreCategoryCreateCommand;
use App\AddHash\System\GlobalContext\Common\BaseServiceController;
use App\AddHash\System\GlobalContext\Validation\Validator;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Swagger\Annotations as SWG;
use Symfony\Component\HttpFoundation\Response;

class StoreCategoryController extends BaseServiceController
{
	protected $storeCategoryListService;
	protected $storeCategoryCreateService;

	public function __construct(
		ListServiceInterface $storeCategoryListService,
		CreateServiceInterface $storeCategoryCreateService
	)
	{
		$this->storeCategoryListService = $storeCategoryListService;
		$this->storeCategoryCreateService = $storeCategoryCreateService;
		$this->setValidator(new Validator());
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

		return $this->json($result, Response::HTTP_OK);
	}


	/**
	 * Create new category
	 *
	 * @SWG\Parameter(
	 *     in="query",
	 *     name="title",
	 *     type="string",
	 *     description="Category title",
	 * )
	 * @SWG\Parameter(
	 *     name="position",
	 *     in="query",
	 *     type="number",
	 *     description="Category position"
	 * )
	 *
	 * @SWG\Response(
	 *     response=200,
	 *     description="Returns ok",
	 * )
	 *
	 * @SWG\Tag(name="Admin Store")
	 * @param Request $request
	 * @return JsonResponse
	 */
	public function create(Request $request)
	{
		$createCommand = new StoreCategoryCreateCommand(
			$request->get('title'),
			$request->get('position', 0)
		);

		if (!$this->commandIsValid($createCommand)) {
			return $this->json([
				'errors' => $this->getLastValidationErrors()
			], Response::HTTP_BAD_REQUEST);
		}

		$this->storeCategoryCreateService->execute($createCommand);

		return $this->json([]);
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