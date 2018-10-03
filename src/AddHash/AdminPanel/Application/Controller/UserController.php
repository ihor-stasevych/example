<?php

namespace App\AddHash\AdminPanel\Application\Controller;

use Swagger\Annotations as SWG;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use App\AddHash\System\GlobalContext\Common\BaseServiceController;
use App\AddHash\AdminPanel\Application\Command\Captcha\ReCaptchaCommand;
use App\AddHash\AdminPanel\Application\Command\User\UserRegisterCommand;
use App\AddHash\AdminPanel\Domain\User\Services\UserRegisterServiceInterface;
use App\AddHash\AdminPanel\Domain\Captcha\Services\ReCaptchaServiceInterface;

class UserController extends BaseServiceController
{
	private $userRegisterService;

	private $container;

	private $captchaService;

	public function __construct(
		UserRegisterServiceInterface $userRegisterService,
		ContainerInterface $container,
        ReCaptchaServiceInterface $captchaService
	)
	{
		$this->container = $container;
		$this->userRegisterService = $userRegisterService;
		$this->captchaService = $captchaService;
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
        $reCaptchaCommand = new ReCaptchaCommand(
            $request->get('g-recaptcha-response'),
            $request->getClientIp(),
            $request->headers->get('User-Agent')
        );

		$userRegisterCommand = new UserRegisterCommand(
			$request->get('email'),
			$request->get('email'),
			$request->get('email'),
			$request->get('password'),
			$request->get('roles', ['ROLE_USER']),
            $request->getClientIp(),
            $request->headers->get('User-Agent')
		);

		if (false === $this->commandIsValid($userRegisterCommand)) {
			return $this->json([
				'errors' => $this->getLastValidationErrors()
			], Response::HTTP_BAD_REQUEST);
		}

		try {
            $this->captchaService->execute($reCaptchaCommand);
			$user = $this->userRegisterService->execute($userRegisterCommand);
		} catch (\Exception $e) {
			return $this->json([
			    'message' => $e->getMessage()
            ], Response::HTTP_BAD_REQUEST);
		}

		return $this->json($this->getHashByUser($user));
	}

	protected function getHashByUser(UserInterface $user)
	{
		$jwtManager = $this->container->get('lexik_jwt_authentication.jwt_manager');

		return ['token' => $jwtManager->create($user)];
	}
}