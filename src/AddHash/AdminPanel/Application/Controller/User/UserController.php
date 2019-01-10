<?php

namespace App\AddHash\AdminPanel\Application\Controller\User;

use Swagger\Annotations as SWG;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use App\AddHash\System\GlobalContext\Common\BaseServiceController;
use App\AddHash\AdminPanel\Application\Command\User\UserCreateCommand;
use App\AddHash\AdminPanel\Domain\User\Services\UserCreateServiceInterface;

class UserController extends BaseServiceController
{
    private $createService;

    public function __construct(UserCreateServiceInterface $createService)
    {
        $this->createService = $createService;
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
     * @SWG\Response(
     *     response=406,
     *     description="Returns validation errors"
     * )
     *
     * @SWG\Response(
     *     response=400,
     *     description="Returns validation errors"
     * )
     *
     * @param Request $request
     * @return JsonResponse
     * @SWG\Tag(name="User")
     */
    public function create(Request $request)
    {
        $command = new UserCreateCommand(
            $request->get('email'),
            $request->get('firstName'),
	        $request->get('lastName'),
            $request->get('password'),
	        $request->get('phone'),
            $request->get('g-recaptcha-response')
        );

        if (false === $this->commandIsValid($command)) {
            return $this->json([
                'errors' => $this->getLastValidationErrors()
            ], Response::HTTP_NOT_ACCEPTABLE);
        }

        return $this->json($this->createService->execute($command));
    }
}