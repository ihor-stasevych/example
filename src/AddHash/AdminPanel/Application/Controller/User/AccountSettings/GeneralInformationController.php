<?php

namespace App\AddHash\AdminPanel\Application\Controller\User\AccountSettings;

use Swagger\Annotations as SWG;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use App\AddHash\System\GlobalContext\Common\BaseServiceController;
use App\AddHash\AdminPanel\Application\Command\User\AccountSettings\GeneralInformationUpdateCommand;
use App\AddHash\AdminPanel\Domain\User\Services\AccountSettings\GeneralInformationGetServiceInterface;
use App\AddHash\AdminPanel\Domain\User\Services\AccountSettings\GeneralInformationUpdateServiceInterface;

class GeneralInformationController extends BaseServiceController
{
    private $getService;

    private $updateService;

    public function __construct(
        GeneralInformationGetServiceInterface $getService,
        GeneralInformationUpdateServiceInterface $updateService
    )
    {
        $this->getService = $getService;
        $this->updateService = $updateService;
    }

    /**
     * Get general information by authorized user
     *
     * @SWG\Response(
     *     response=200,
     *     description="Returns the general information of an user",
     *     @SWG\Schema(
     *              type="object",
     *              @SWG\Property(property="email", type="string"),
     *              @SWG\Property(property="backupEmail", type="string"),
     *              @SWG\Property(property="firstName", type="string"),
     *              @SWG\Property(property="lastName", type="string"),
     *              @SWG\Property(property="phone", type="string")
     *     )
     * )
     *
     * @return JsonResponse
     * @SWG\Tag(name="User")
     */
    public function get()
    {
        return $this->json($this->getService->execute());
    }

    /**
     * Update general information by authorized user
     *
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
     *
     * @SWG\Response(
     *     response=200,
     *     description="Returns the general information of an user",
     *     @SWG\Schema(
     *              type="object",
     *              @SWG\Property(property="email", type="string"),
     *              @SWG\Property(property="backupEmail", type="string"),
     *              @SWG\Property(property="firstName", type="string"),
     *              @SWG\Property(property="lastName", type="string"),
     *              @SWG\Property(property="phone", type="string")
     *     )
     * )
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
    public function update(Request $request)
	{
	    $command = new GeneralInformationUpdateCommand(
	        $request->get('email'),
            $request->get('backupEmail'),
            $request->get('firstName'),
            $request->get('lastName'),
            $request->get('phone'),
            $request->get('isMonthlyNewsletter', 0)
        );

        if (false === $this->commandIsValid($command)) {
            return $this->json([
                'errors' => $this->getLastValidationErrors(),
            ], Response::HTTP_NOT_ACCEPTABLE);
        }

		return $this->json($this->updateService->execute($command));
	}
}