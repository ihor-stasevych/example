<?php

namespace App\AddHash\AdminPanel\Application\Controller;

use App\AddHash\AdminPanel\Domain\User\Services\UserRegisterServiceInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Swagger\Annotations as SWG;

class UserController
{
	private $userRegisterService;

	public function __construct(UserRegisterServiceInterface $userRegisterService)
	{
		$this->userRegisterService = $userRegisterService;
	}


	/**
	 * Register new user
	 *
	 * @SWG\Parameter(
	 *     name="userName",
	 *     in="query",
	 *     type="string",
	 *     description="User name"
	 * )
	 * @SWG\Parameter(
	 *     name="email",
	 *     in="query",
	 *     type="string",
	 *     description="User E-mail"
	 * )
	 * @SWG\Parameter(
	 *     name="backupEmail",
	 *     in="query",
	 *     type="string",
	 *     description="User backup E-mail"
	 * )
	 * @SWG\Parameter(
	 *     name="password",
	 *     in="query",
	 *     type="string",
	 *     description="User password"
	 * )
	 * @SWG\Parameter(
	 *     name="firstName",
	 *     in="query",
	 *     type="string",
	 *     description="User first name"
	 * )
	 * @SWG\Parameter(
	 *     name="lastName",
	 *     in="query",
	 *     type="string",
	 *     description="User last name"
	 * )
	 * @SWG\Parameter(
	 *     name="phone",
	 *     in="query",
	 *     type="number",
	 *     description="User phone number"
	 * )
	 * @SWG\Response(
	 *     response=200,
	 *     description="Returns new user"
	 * )
	 *
	 * @param Request $request
	 * @return JsonResponse
	 * @SWG\Tag(name="User")
	 */
	public function register(Request $request)
	{
		$data = [
			'userName' => $request->get('userName'),
			'email' => $request->get('email'),
			'backupEmail' => $request->get('backupEmail'),
			'password' => $request->get('password'),
			'firstName' => $request->get('firstName'),
			'lastName' => $request->get('lastName'),
			'phone' => $request->get('phone')
		];


		var_dump($this->userRegisterService->execute($data));

		return new JsonResponse(['message' => true], 200);
	}
}