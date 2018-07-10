<?php

namespace App\AddHash\AdminPanel\Application\Controller;

use App\AddHash\AdminPanel\Domain\User\Services\UserRegisterServiceInterface;
use App\AddHash\AdminPanel\Infrastructure\Command\User\UserRegisterCommand;
use App\AddHash\System\GlobalContext\Common\BaseServiceController;
use App\AddHash\System\GlobalContext\Validation\Validator;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Swagger\Annotations as SWG;
use Symfony\Component\HttpFoundation\Response;

class UserController extends BaseServiceController
{
	private $userRegisterService;

	public function __construct(UserRegisterServiceInterface $userRegisterService)
	{
		$this->userRegisterService = $userRegisterService;
		$this->setValidator(new Validator());
	}


	/**
	 * Register new user
	 *
	 * @SWG\Parameter(
	 *     in="query",
	 *     name="userName",
	 *     type="string",
	 *     description="User name",
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
	 *
	 * @SWG\Parameter(
	 *     name="confirmPassword",
	 *     in="query",
	 *     type="string",
	 *     description="Confirm user password"
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
	 *   @SWG\Response(
	 *     response=400,
	 *     description="Returns validation errors"
	 * )
	 *
	 * @param Request $request
	 * @return JsonResponse
	 * @SWG\Tag(name="User")
	 */
	public function register(Request $request)
	{
		$userRegisterCommand = new UserRegisterCommand(
			$request->get('userName'),
			$request->get('email'),
			$request->get('backupEmail'),
			$request->get('password'),
			$request->get('confirmPassword'),
			$request->get('firstName'),
			$request->get('lastName'),
			$request->get('phone'),
			$request->get('roles', ['ROLE_USER'])
		);

		if (!$this->commandIsValid($userRegisterCommand)) {
			return $this->json([
				'errors' => $this->getLastValidationErrors()
			], Response::HTTP_BAD_REQUEST);
		}

		try {
			$this->userRegisterService->execute($userRegisterCommand);
		} catch (\Exception $e) {
			return $this->json(['message' =>$e->getMessage()], Response::HTTP_BAD_REQUEST);
		}

		return $this->json([]);
	}
}