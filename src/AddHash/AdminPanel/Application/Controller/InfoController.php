<?php

namespace App\AddHash\AdminPanel\Application\Controller;

use App\AddHash\AdminPanel\Domain\Info\Services\GetCryptoCurrenciesServiceInterface;
use App\AddHash\System\GlobalContext\Common\BaseServiceController;
use Swagger\Annotations as SWG;

class InfoController extends BaseServiceController
{
	private $getCryptoCurrenciesService;

	public function __construct(GetCryptoCurrenciesServiceInterface $currenciesService)
	{
		$this->getCryptoCurrenciesService = $currenciesService;
	}

	/**
	 *
	 *
	 * @SWG\Response(
	 *     response=200,
	 *     description="Return list crypto currencies"
	 * )
	 *
	 * @SWG\Tag(name="Information")
	 * @return \Symfony\Component\HttpFoundation\JsonResponse
	 */
	public function getCryptoCurrencies()
	{
		return $this->json($this->getCryptoCurrenciesService->execute());
	}
}