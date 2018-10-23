<?php

namespace App\AddHash\AdminPanel\Application\Controller;

use Swagger\Annotations as SWG;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use App\AddHash\System\GlobalContext\Common\BaseServiceController;
use App\AddHash\AdminPanel\Application\Command\PromoContact\PromoContactCreateCommand;
use App\AddHash\AdminPanel\Domain\PromoContact\Services\PromoContactCreateServiceInterface;

class PromoContactController extends BaseServiceController
{
    private $createService;

	public function __construct(PromoContactCreateServiceInterface $createService)
	{
        $this->createService = $createService;
	}

    /**
     * Register new user
     *
     * @SWG\Parameter(
     *     in="query",
     *     name="email",
     *     required=true,
     *     type="string",
     *     description="Email",
     * )
     *
     * @SWG\Parameter(
     *     in="query",
     *     name="gender",
     *     required=true,
     *     type="string",
     *     description="Mr or Mrs (1 or 0)",
     * )
     *
     * @SWG\Parameter(
     *     in="query",
     *     name="name",
     *     required=true,
     *     type="string",
     *     description="Name",
     * )
     *
     * @SWG\Parameter(
     *     in="query",
     *     name="message",
     *     required=true,
     *     type="string",
     *     description="Message",
     * )
     *
     * @SWG\Response(
     *     response=200,
     *     description="Return success"
     * )
     *
     * @SWG\Response(
     *     response=400,
     *     description="Return validation errors"
     * )
     *
     * @param Request $request
     * @return JsonResponse
     * @SWG\Tag(name="Promo contact")
     */
	public function create(Request $request)
	{
	    $command = new PromoContactCreateCommand(
	        $request->get('email'),
            $request->get('name'),
            $request->get('message'),
            $request->get('gender')
        );

        if (false === $this->commandIsValid($command)) {
			return $this->json([
				'errors' => $this->getLastValidationErrors()
			], Response::HTTP_NOT_ACCEPTABLE);
		}

        $this->createService->execute($command);

        return $this->json([]);
	}
}