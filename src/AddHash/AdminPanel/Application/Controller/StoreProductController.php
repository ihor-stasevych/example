<?php

namespace App\AddHash\AdminPanel\Application\Controller;


use App\AddHash\AdminPanel\Infrastructure\Repository\Store\Product\StoreProductRepository;
use App\AddHash\AdminPanel\Infrastructure\Transformers\Store\StoreProductTransform;
use App\AddHash\System\GlobalContext\Common\BaseServiceController;
use League\Fractal\Manager;
use League\Fractal\Resource\Collection;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;

class StoreProductController extends BaseServiceController
{
	private $repo;

	public function __construct(StoreProductRepository $repository)
	{
		$this->repo = $repository;

	}

	/**
	 * @return JsonResponse
	 */
	public function index()
	{
		return $this->json($this->repo->listAllProducts());
	}
}