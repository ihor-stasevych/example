<?php

namespace App\AddHash\MinerPanel\Infrastructure\Repository\Package;

use App\AddHash\MinerPanel\Domain\Package\Model\Package;
use App\AddHash\MinerPanel\Domain\Package\Repository\PackageRepositoryInterface;
use Doctrine\ORM\ORMException;
use Doctrine\ORM\OptimisticLockException;
use App\AddHash\System\GlobalContext\Repository\AbstractRepository;

class PackageRepository extends AbstractRepository implements PackageRepositoryInterface
{
	/**
	 * @param int $id
	 * @return Package|null
	 */
    public function get(int $id): ?Package
    {
        /** @var Package $pack */
        $pack = $this->entityRepository->find($id);

        return $pack;
    }

    /**
     * @param Package $package
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function save(Package $package): void
    {
        $this->entityManager->persist($package);
        $this->entityManager->flush();
    }

	/**
	 * @return mixed
	 * @throws \Doctrine\ORM\NonUniqueResultException
	 */
    public function getDefaultPackage()
    {
	    return $this->entityRepository->createQueryBuilder('p')
		    ->select('p')
		    ->where('p.isDefaultPackage = :default')
		    ->setParameter('default', true)
		    ->getQuery()
		    ->getOneOrNullResult();
    }

	protected function getEntityName(): string
    {
        return Package::class;
    }
}