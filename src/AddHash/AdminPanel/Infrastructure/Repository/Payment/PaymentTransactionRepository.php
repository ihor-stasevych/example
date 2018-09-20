<?php

namespace App\AddHash\AdminPanel\Infrastructure\Repository\Payment;

use Doctrine\ORM\ORMException;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\NonUniqueResultException;
use App\AddHash\AdminPanel\Domain\Payment\PaymentTransaction;
use App\AddHash\System\GlobalContext\Repository\AbstractRepository;
use App\AddHash\AdminPanel\Domain\Payment\Repository\PaymentTransactionRepositoryInterface;

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
     * @param string $externalId
     * @return PaymentTransaction|null
     * @throws NonUniqueResultException
     */
	public function findByExternalId(string $externalId): ?PaymentTransaction
    {
        return $this->entityRepository->createQueryBuilder('pt')
            ->select('pt')
            ->where('pt.externalId = :externalId')
            ->setParameter('externalId', $externalId)
            ->getQuery()
            ->getOneOrNullResult();
    }

    /**
     * @param int $paymentId
     * @return mixed
     * @throws NonUniqueResultException
     */
    public function findByPaymentId(int $paymentId): PaymentTransaction
    {
        return $this->entityRepository->createQueryBuilder('pt')
            ->select('pt')
            ->where('pt.payment = :paymentId')
            ->setParameter('paymentId', $paymentId)
            ->getQuery()
            ->getOneOrNullResult();
    }

	/**
	 * @param PaymentTransaction $paymentTransaction
	 * @throws ORMException
	 * @throws OptimisticLockException
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