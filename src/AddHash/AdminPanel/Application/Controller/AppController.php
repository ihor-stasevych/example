<?php

namespace App\AddHash\AdminPanel\Application\Controller;


use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

class AppController
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