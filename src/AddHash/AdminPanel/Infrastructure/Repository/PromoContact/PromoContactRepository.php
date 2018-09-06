<?php

namespace App\AddHash\AdminPanel\Infrastructure\Repository\PromoContact;

use Doctrine\ORM\ORMException;
use Doctrine\ORM\OptimisticLockException;
use App\AddHash\AdminPanel\Domain\PromoContact\PromoContact;
use App\AddHash\System\GlobalContext\Repository\AbstractRepository;
use App\AddHash\AdminPanel\Domain\PromoContact\PromoContactRepositoryInterface;

class PromoContactRepository extends AbstractRepository implements PromoContactRepositoryInterface
{
    /**
     * @param PromoContact $promoContact
     * @throws OptimisticLockException
     * @throws ORMException
     */
	public function save(PromoContact $promoContact)
	{
		$this->entityManager->persist($promoContact);
		$this->entityManager->flush($promoContact);
	}

	/**
	 * @return string
	 */
	protected function getEntityName()
	{
		return PromoContact::class;
	}
}