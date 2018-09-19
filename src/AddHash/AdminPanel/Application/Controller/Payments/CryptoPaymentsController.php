<?php

namespace App\AddHash\AdminPanel\Application\Controller\Payments;

use Swagger\Annotations as SWG;
use App\AddHash\AdminPanel\Domain\User\User;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use App\AddHash\System\GlobalContext\ValueObject\CryptoPayment;
use App\AddHash\System\GlobalContext\Common\BaseServiceController;
use App\AddHash\AdminPanel\Application\Command\Payments\MakeCryptoPaymentCommand;
use App\AddHash\AdminPanel\Application\Command\Payments\GetStateCryptoPaymentCommand;
use App\AddHash\AdminPanel\Application\Command\Payments\CallbackCryptoPaymentCommand;
use App\AddHash\AdminPanel\Domain\Payment\Services\MakeCryptoPaymentServiceInterface;
use App\AddHash\AdminPanel\Domain\Payment\Services\GetCryptoCurrenciesServiceInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use App\AddHash\AdminPanel\Domain\Payment\Services\GetStateCryptoPaymentServiceInterface;
use App\AddHash\AdminPanel\Domain\Payment\Services\CallbackCryptoPaymentServiceInterface;


class CryptoPaymentsController extends BaseServiceController
{
	private $cryptoPaymentService;

	private $tokenStorage;

	private $currenciesService;

	private $callbackCryptoPaymentService;

	private $getStateCryptoPaymentService;

	public function __construct(
		MakeCryptoPaymentServiceInterface $cryptoPaymentService,
		TokenStorageInterface $tokenStorage,
		GetCryptoCurrenciesServiceInterface $currenciesService,
        CallbackCryptoPaymentServiceInterface $callbackCryptoPaymentService,
        GetStateCryptoPaymentServiceInterface $getStateCryptoPaymentService
	)
	{
		$this->cryptoPaymentService = $cryptoPaymentService;
		$this->tokenStorage = $tokenStorage;
		$this->currenciesService = $currenciesService;
		$this->callbackCryptoPaymentService = $callbackCryptoPaymentService;
		$this->getStateCryptoPaymentService = $getStateCryptoPaymentService;
	}

	/**
	 * @SWG\Parameter(
	 *     name="currency",
	 *     in="path",
	 *     type="string",
	 *     required=true,
	 *     description="Type of cryptocurrency"
	 * )
	 *
	 * @SWG\Tag(name="Payments")
	 * @param $currency
	 *
	 * @SWG\Response(
	 *     response=200,
	 *     description="Returns new order or existing"
	 * )
	 *
	 * @return CryptoPayment|JsonResponse
	 */
	public function createNewPayment($currency)
	{
		/** @var User $user */
		$user = $this->tokenStorage->getToken()->getUser();

		$command = new MakeCryptoPaymentCommand($currency);

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

		return $this->json($cryptoPayment->getData());
	}

	/**
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
		return $this->json($this->currenciesService->execute());
	}

	public function getState(int $orderId)
	{
        $command = new GetStateCryptoPaymentCommand($orderId);

        if (!$this->commandIsValid($command)) {
            return $this->json([
                'errors' => $this->getLastValidationErrors()
            ], Response::HTTP_BAD_REQUEST);
        }

        try{
            $data = $this->getStateCryptoPaymentService->execute($command);
        } catch (\Exception $e) {
            return $this->json([
                'errors' => $e->getMessage()
            ], Response::HTTP_BAD_REQUEST);
        }

        return $this->json($data);
	}

	public function callback(int $orderId)
    {
        $command = new CallbackCryptoPaymentCommand(
            $orderId,
            file_get_contents('php://input')
        );

        if (!$this->commandIsValid($command)) {
            return $this->json([
                'errors' => $this->getLastValidationErrors()
            ], Response::HTTP_BAD_REQUEST);
        }

        try{
            $data = $this->callbackCryptoPaymentService->execute($command);
        } catch (\Exception $e) {
            return $this->json([
                'errors' => $e->getMessage()
            ], Response::HTTP_BAD_REQUEST);
        }

        return $this->json($data);
    }
}