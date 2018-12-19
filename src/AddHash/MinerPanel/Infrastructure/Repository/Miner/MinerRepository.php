<?php

namespace App\AddHash\MinerPanel\Infrastructure\Repository\Miner;

use Pagerfanta\Pagerfanta;
use Doctrine\ORM\ORMException;
use Doctrine\ORM\OptimisticLockException;
use Pagerfanta\Adapter\DoctrineORMAdapter;
use Doctrine\ORM\NonUniqueResultException;
use App\AddHash\MinerPanel\Domain\User\User;
use App\AddHash\MinerPanel\Domain\Miner\Miner;
use App\AddHash\MinerPanel\Domain\Miner\MinerRepositoryInterface;
use App\AddHash\System\GlobalContext\Repository\AbstractRepository;

class MinerRepository extends AbstractRepository implements MinerRepositoryInterface
{
    public function getMinersByUser(User $user, ?int $currentPage): ?Pagerfanta
    {
        $result = $this->entityManager
            ->getRepository($this->getEntityName())
            ->createQueryBuilder('m')
            ->select('m', 'cr', 't', 'a')
            ->join('m.credential', 'cr')
            ->join('m.type', 't')
            ->join('m.algorithm', 'a')
            ->where('m.user = :user')
            ->setParameter('user', $user)
            ->getQuery();

        $pager = new Pagerfanta(
            new DoctrineORMAdapter($result)
        );

        $pager->setMaxPerPage(Miner::MAX_PER_PAGE);
        $pager->setCurrentPage($currentPage ?? 1);

        return $pager;
    }

    /**
     * @param int $id
     * @param User $user
     * @return Miner|null
     * @throws NonUniqueResultException
     */
    public function getMinerByIdAndUser(int $id, User $user): ?Miner
    {
        return $this->entityManager
            ->getRepository($this->getEntityName())
            ->createQueryBuilder('m')
            ->select('m', 'cr', 't', 'a', 'c')
            ->join('m.credential', 'cr')
            ->join('m.type', 't')
            ->join('m.algorithm', 'a')
            ->join('a.coins', 'c')
            ->where('m.id = :id')
            ->andWhere('m.user = :user')
            ->setParameter('id', $id)
            ->setParameter('user', $user)
            ->getQuery()
            ->getOneOrNullResult();
    }

    /**
     * @param int $id
     * @param User $user
     * @return Miner|null
     * @throws NonUniqueResultException
     */
    public function existMinerByIdAndUser(int $id, User $user): ?Miner
    {
        return $this->entityManager
            ->getRepository($this->getEntityName())
            ->createQueryBuilder('m')
            ->select('m', 'cr')
            ->join('m.credential', 'cr')
            ->where('m.id = :id')
            ->andWhere('m.user = :user')
            ->setParameter('id', $id)
            ->setParameter('user', $user)
            ->getQuery()
            ->getOneOrNullResult();
    }

    /**
     * @param string $title
     * @param User $user
     * @return Miner|null
     * @throws NonUniqueResultException
     */
    public function getMinerByTitleAndUser(string $title, User $user): ?Miner
    {
        return $this->entityManager
            ->getRepository($this->getEntityName())
            ->createQueryBuilder('m')
            ->select('m', 'cr', 't', 'a', 'c')
            ->join('m.credential', 'cr')
            ->join('m.type', 't')
            ->join('m.algorithm', 'a')
            ->join('a.coins', 'c')
            ->where('m.title = :title')
            ->andWhere('m.user = :user')
            ->setParameter('title', $title)
            ->setParameter('user', $user)
            ->getQuery()
            ->getOneOrNullResult();
    }

    /**
     * @param User $user
     * @return int
     * @throws NonUniqueResultException
     */
    public function getCountByUser(User $user): int
    {
        return $this->entityManager
            ->getRepository($this->getEntityName())
            ->createQueryBuilder('m')
            ->select('count(m.id)')
            ->where('m.user = :user')
            ->setParameter('user', $user)
            ->getQuery()
            ->getSingleScalarResult();
    }

    /**
     * @param int $id
     * @return Miner|null
     * @throws NonUniqueResultException
     */
    public function get(int $id): ?Miner
    {
        return $this->entityManager
            ->getRepository($this->getEntityName())
            ->createQueryBuilder('m')
            ->select('m')
            ->where('m.id = :id')
            ->setParameter('id', $id)
            ->getQuery()
            ->getOneOrNullResult();
    }

    /**
     * @param Miner $miner
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function save(Miner $miner): void
    {
        $this->entityManager->persist($miner);
        $this->entityManager->flush();
    }

    /**
     * @param Miner $miner
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function delete(Miner $miner): void
    {
        $this->entityManager->remove($miner);
        $this->entityManager->flush();
    }

    protected function getEntityName(): string
    {
        return Miner::class;
    }
}