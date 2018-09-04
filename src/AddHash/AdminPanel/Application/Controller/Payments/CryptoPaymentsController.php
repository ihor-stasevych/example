<?php

namespace App\AddHash\AdminPanel\Application\Controller\Payments;


use App\AddHash\AdminPanel\Application\Command\Payments\MakeCryptoPaymentCommand;
use App\AddHash\AdminPanel\Domain\Payment\Services\GetCryptoCurrenciesServiceInterface;
use App\AddHash\AdminPanel\Domain\Payment\Services\MakeCryptoPaymentServiceInterface;
use App\AddHash\AdminPanel\Domain\User\User;
use App\AddHash\System\GlobalContext\Common\BaseServiceController;
use App\AddHash\System\GlobalContext\ValueObject\CryptoPayment;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Swagger\Annotations as SWG;

class CryptoPaymentsController extends BaseServiceController
{
	private $cryptoPaymentService;
	private $tokenStorage;
	private $currenciesService;

	public function __construct(
		MakeCryptoPaymentServiceInterface $cryptoPaymentService,
		TokenStorageInterface $tokenStorage,
		GetCryptoCurrenciesServiceInterface $currenciesService
	)
	{
		$this->cryptoPaymentService = $cryptoPaymentService;
		$this->tokenStorage = $tokenStorage;
		$this->currenciesService = $currenciesService;
	}


	/**
	 * @SWG\Parameter(
	 *     name="currency",
	 *     in="formData",
	 *     type="string",
	 *     required=true,
	 *     description="Type of cryptocurrency"
	 * )
	 *
	 * @SWG\Parameter(
	 *     name="amount",
	 *     in="formData",
	 *     type="string",
	 *     required=true,
	 *     description="Amount of cryptocurrency"
	 * )
	 *
	 *
	 * @SWG\Tag(name="Payments")
	 * @param Request $request
	 *
	 * @SWG\Response(
	 *     response=200,
	 *     description="Returns new order or existing"
	 * )
	 *
	 * @return CryptoPayment|JsonResponse
	 */
	public function createNewPayment(Request $request)
	{
		/** @var User $user */
		$user = $this->tokenStorage->getToken()->getUser();

		$command = new MakeCryptoPaymentCommand(
			$request->get('currency'),
			$request->get('amount')
		);

		if (!$this->commandIsValid($command)) {
			return $this->json([
				'errors' => $this->getLastValidationErrors()
			], Response::HTTP_BAD_REQUEST);
		}

		try{
			/** @var CryptoPayment $cryptoPayment */
			$cryptoPayment = $this->cryptoPaymentService->execute($user, $command);
		} catch (\Exception $e) {
			return $this->json([
				'errors' => $e->getMessage()
			], Response::HTTP_BAD_REQUEST);
		}

		return $this->json([
			'address' => $cryptoPayment->getAddress()
		]);
	}

	/**
	 *
	 * @SWG\Tag(name="Payments")
	 *
	 * @SWG\Response(
	 *     response=200,
	 *     description="Returns available currencies data"
	 * )
	 * @return JsonResponse
	 */
	public function getCurrencies()
	{
		$data = $this->currenciesService->execute();
		return $this->json($data);
	}

}