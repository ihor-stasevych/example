<?php

namespace App\AddHash\AdminPanel\Infrastructure\Repository\Payment;

use App\AddHash\AdminPanel\Domain\Payment\PaymentMethod;
use App\AddHash\AdminPanel\Domain\Payment\Repository\PaymentGatewayRepositoryInterface;
use App\AddHash\System\GlobalContext\Repository\AbstractRepository;

class PaymentGatewayRepository extends AbstractRepository implements PaymentGatewayRepositoryInterface
{
	/**
	 * @param string $name
	 * @return mixed
	 * @throws \Doctrine\ORM\NonUniqueResultException
	 */
	public function getByName(string $name)
	{
		$res = $this->entityRepository->createQueryBuilder('pmr')
			->select('pmr')
			->where('pmr.name = :name')
			->setParameter('name', $name)
			->getQuery();

		return $res->getOneOrNullResult();
	}

	/**
	 * @return string
	 */
	protected function getEntityName()
	{
		return PaymentMethod::class;
	}
}