<?php

namespace App\AddHash\AdminPanel\Application\Controller\Payments;

use Swagger\Annotations as SWG;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use App\AddHash\System\GlobalContext\ValueObject\CryptoPayment;
use App\AddHash\System\GlobalContext\Common\BaseServiceController;
use App\AddHash\AdminPanel\Application\Command\Payments\MakeCryptoPaymentCommand;
use App\AddHash\AdminPanel\Application\Command\Payments\GetStateCryptoPaymentCommand;
use App\AddHash\AdminPanel\Application\Command\Payments\CallbackCryptoPaymentCommand;
use App\AddHash\AdminPanel\Domain\Payment\Services\MakeCryptoPaymentServiceInterface;
use App\AddHash\AdminPanel\Domain\Payment\Services\GetCryptoCurrenciesServiceInterface;
use App\AddHash\AdminPanel\Domain\Payment\Services\GetStateCryptoPaymentServiceInterface;
use App\AddHash\AdminPanel\Domain\Payment\Services\CallbackCryptoPaymentServiceInterface;
use App\AddHash\AdminPanel\Application\Command\Payments\GetCurrenciesCryptoPaymentCommand;

class CryptoPaymentsController extends BaseServiceController
{
	private $cryptoPaymentService;

	private $currenciesService;

	private $callbackCryptoPaymentService;

	private $getStateCryptoPaymentService;

	public function __construct(
		MakeCryptoPaymentServiceInterface $cryptoPaymentService,
		GetCryptoCurrenciesServiceInterface $currenciesService,
        CallbackCryptoPaymentServiceInterface $callbackCryptoPaymentService,
        GetStateCryptoPaymentServiceInterface $getStateCryptoPaymentService
	)
	{
		$this->cryptoPaymentService = $cryptoPaymentService;
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
     * @SWG\Parameter(
     *     name="orderId",
     *     in="path",
     *     type="integer",
     *     required=true,
     *     description="Order ID"
     * )
	 *
     * @SWG\Tag(name="Payments")
     * @param $orderId
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
	public function createNewPayment(int $orderId, string $currency)
	{
		$command = new MakeCryptoPaymentCommand($orderId, $currency);

		if (false === $this->commandIsValid($command)) {
			return $this->json([
				'errors' => $this->getLastValidationErrors()
			], Response::HTTP_BAD_REQUEST);
		}

		try {
			/** @var CryptoPayment $cryptoPayment */
			$cryptoPayment = $this->cryptoPaymentService->execute($command);
		} catch (\Exception $e) {
			return $this->json([
				'errors' => $e->getMessage()
			], Response::HTTP_BAD_REQUEST);
		}

		return $this->json($cryptoPayment);
	}

	/**
	 * @SWG\Tag(name="Payments")
	 *
     * @SWG\Tag(name="Payments")
     * @param $orderId
     *
	 * @SWG\Response(
	 *     response=200,
	 *     description="Returns available currencies data"
	 * )
	 * @return JsonResponse
	 */
	public function getCurrencies(int $orderId)
	{
        $command = new GetCurrenciesCryptoPaymentCommand($orderId);

        if (false === $this->commandIsValid($command)) {
            return $this->json([
                'errors' => $this->getLastValidationErrors()
            ], Response::HTTP_BAD_REQUEST);
        }

		return $this->json($this->currenciesService->execute($command));
	}

    /**
     * @SWG\Tag(name="Payments")
     *
     * @SWG\Tag(name="Payments")
     * @param $orderId
     *
     * @SWG\Response(
     *     response=200,
     *     description="Returns state"
     * )
     * @return JsonResponse
     */
	public function getState(int $orderId)
	{
        $command = new GetStateCryptoPaymentCommand($orderId);

        if (false === $this->commandIsValid($command)) {
            return $this->json([
                'errors' => $this->getLastValidationErrors()
            ], Response::HTTP_BAD_REQUEST);
        }

        try {
            $data = $this->getStateCryptoPaymentService->execute($command);
        } catch (\Exception $e) {
            return $this->json([
                'errors' => $e->getMessage()
            ], Response::HTTP_BAD_REQUEST);
        }

        return $this->json($data);
	}

    /**
     * @SWG\Tag(name="Payments")
     *
     * @SWG\Tag(name="Payments")
     * @param $orderId
     *
     * @SWG\Response(
     *     response=200,
     *     description="Returns callback"
     * )
     * @return JsonResponse
     */
	public function callback(int $orderId)
    {
        $command = new CallbackCryptoPaymentCommand(
            $orderId,
            file_get_contents('php://input')
        );

        if (false === $this->commandIsValid($command)) {
            return $this->json([
                'errors' => $this->getLastValidationErrors()
            ], Response::HTTP_BAD_REQUEST);
        }

        try {
            $data = $this->callbackCryptoPaymentService->execute($command);
        } catch (\Exception $e) {
            return $this->json([
                'errors' => $e->getMessage()
            ], Response::HTTP_BAD_REQUEST);
        }

        return $this->json($data);
    }
}