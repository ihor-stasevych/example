<?php

namespace App\AddHash\AdminPanel\Infrastructure\Repository\Payment;


use App\AddHash\AdminPanel\Domain\Payment\Payment;
use App\AddHash\AdminPanel\Domain\Payment\Repository\PaymentRepositoryInterface;
use App\AddHash\System\GlobalContext\Repository\AbstractRepository;

class PaymentRepository extends AbstractRepository implements PaymentRepositoryInterface
{

	/***
	 * @param $id
	 * @return null|object|Payment
	 */
	public function findById($id)
	{
		return $this->entityRepository->find($id);
	}

	/**
	 * @param Payment $payment
	 * @throws \Doctrine\ORM\ORMException
	 * @throws \Doctrine\ORM\OptimisticLockException
	 */
	public function save(Payment $payment)
	{
		$this->entityManager->persist($payment);
		$this->entityManager->flush($payment);
	}

	/**
	 * @return string
	 */
	protected function getEntityName()
	{
		return Payment::class;
	}
}