<?php

namespace App\AddHash\AdminPanel\Infrastructure\Repository\Payment;

use App\AddHash\AdminPanel\Domain\Payment\Payment;
use App\AddHash\AdminPanel\Domain\Payment\PaymentTransaction;
use App\AddHash\AdminPanel\Domain\Payment\Repository\PaymentTransactionRepositoryInterface;
use App\AddHash\System\GlobalContext\Repository\AbstractRepository;

class PaymentTransactionRepository extends AbstractRepository implements PaymentTransactionRepositoryInterface
{
	/***
	 * @param $id
	 * @return null|object|PaymentTransaction
	 */
	public function findById($id)
	{
		return $this->entityRepository->find($id);
	}

	/**
	 * @param PaymentTransaction $paymentTransaction
	 * @throws \Doctrine\ORM\ORMException
	 * @throws \Doctrine\ORM\OptimisticLockException
	 */
	public function save(PaymentTransaction $paymentTransaction)
	{
		$this->entityManager->persist($paymentTransaction);
		$this->entityManager->flush($paymentTransaction);
	}

	/**
	 * @return string
	 */
	protected function getEntityName()
	{
		return PaymentTransaction::class;
	}
}