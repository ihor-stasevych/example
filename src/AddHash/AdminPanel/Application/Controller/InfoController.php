<?php

namespace App\AddHash\AdminPanel\Application\Controller;

use Swagger\Annotations as SWG;
use Symfony\Component\HttpFoundation\JsonResponse;
use App\AddHash\System\GlobalContext\Common\BaseServiceController;
use App\AddHash\AdminPanel\Domain\Info\Services\GetCryptoCurrenciesServiceInterface;

class InfoController extends BaseServiceController
{
	private $getCryptoCurrenciesService;

	public function __construct(GetCryptoCurrenciesServiceInterface $currenciesService)
	{
		$this->getCryptoCurrenciesService = $currenciesService;
	}

	/**
	 * @SWG\Response(
	 *     response=200,
	 *     description="Return list crypto currencies"
	 * )
	 *
	 * @SWG\Tag(name="Information")
	 * @return JsonResponse
	 */
	public function getCryptoCurrencies()
	{
		return $this->json($this->getCryptoCurrenciesService->execute());
	}
}