<?php

namespace App\AddHash\AdminPanel\Application\Controller;


use App\AddHash\System\GlobalContext\Common\BaseServiceController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

class AppController extends BaseServiceController
{

	/**
	 * Test controller
	 * @param Request $request
	 * @return JsonResponse
	 */
	public function index(Request $request)
	{
		return new JsonResponse([], 200);
	}

}