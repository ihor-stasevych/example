<?php

namespace App\AddHash\AdminPanel\Infrastructure\Repository\Payment;

use Doctrine\ORM\NonUniqueResultException;
use App\AddHash\AdminPanel\Domain\Payment\PaymentMethod;
use App\AddHash\System\GlobalContext\Repository\AbstractRepository;
use App\AddHash\AdminPanel\Domain\Payment\Repository\PaymentMethodRepositoryInterface;

class PaymentMethodRepository extends AbstractRepository implements PaymentMethodRepositoryInterface
{
	/**
	 * @param string $name
	 * @return mixed
	 * @throws NonUniqueResultException
	 */
	public function getByName(string $name): ?PaymentMethod
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