<?php

namespace App\AddHash\AdminPanel\Application\Controller;


use App\AddHash\AdminPanel\Domain\Store\Category\Services\ListServiceInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

use Nelmio\ApiDocBundle\Annotation;

class CategoryController
{
	protected $storeCategoryListService;

	public function __construct(ListServiceInterface $storeCategoryListService)
	{
		$this->storeCategoryListService = $storeCategoryListService;
	}

	/**
	 *
	 * @param Request $request
	 * @return JsonResponse
	 */
	public function index(Request $request)
	{
		$result =  $this->storeCategoryListService->execute();

		return new JsonResponse($result, 200);
	}
}