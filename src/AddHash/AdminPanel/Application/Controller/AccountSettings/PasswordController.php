<?php

namespace App\AddHash\AdminPanel\Application\Controller\AccountSettings;

use Swagger\Annotations as SWG;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use App\AddHash\System\GlobalContext\Common\BaseServiceController;

class PasswordController extends BaseServiceController
{
    public function __construct()
    {

    }

    public function update(Request $request)
	{
		return new JsonResponse([]);
	}
}