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
    public function getMinersByUserWithPagination(User $user, ?int $currentPage): Pagerfanta
    {
        $result = $this->entityManager
            ->getRepository($this->getEntityName())
            ->createQueryBuilder('m')
            ->select('m', 'cr', 't', 'a', 'r')
            ->join('m.credential', 'cr')
            ->join('m.type', 't')
            ->join('m.algorithm', 'a')
            ->leftJoin('m.rigs', 'r')
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

    public function getMinersByUser(User $user): array
    {
        return $this->entityManager
            ->getRepository($this->getEntityName())
            ->createQueryBuilder('m')
            ->select('m')
            ->where('m.user = :user')
            ->setParameter('user', $user)
            ->getQuery()
            ->getResult();
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
            ->select('m', 'cr', 't', 'a', 'c', 'r')
            ->join('m.credential', 'cr')
            ->join('m.type', 't')
            ->join('m.algorithm', 'a')
            ->leftJoin('a.coins', 'c')
            ->leftJoin('m.rigs', 'r')
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
            ->select('m')
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
    public function existMinerByIdAndUserForDelete(int $id, User $user): ?Miner
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
            ->select('m')
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

    public function getMinersWithoutRigs(array $ids, User $user): array
    {
        return $this->entityManager
            ->getRepository($this->getEntityName())
            ->createQueryBuilder('m')
            ->select('m', 'r')
            ->leftJoin('m.rigs', 'r')
            ->where('m.id IN (:ids)')
            ->andWhere('m.user = :user')
            ->andWhere('r.id IS NULL')
            ->setParameter('ids', $ids)
            ->setParameter('user', $user)
            ->getQuery()
            ->getResult();
    }

    public function getMinersByIdsAndUser(array $ids, User $user): array
    {
        return $this->entityManager
            ->getRepository($this->getEntityName())
            ->createQueryBuilder('m')
            ->select('m', 'r')
            ->leftJoin('m.rigs', 'r')
            ->where('m.id IN (:ids)')
            ->andWhere('m.user = :user')
            ->setParameter('ids', $ids)
            ->setParameter('user', $user)
            ->getQuery()
            ->getResult();
    }

    public function getMinersStatusByIdsAndUser(array $ids, User $user): array
    {
        return $this->entityManager
            ->getRepository($this->getEntityName())
            ->createQueryBuilder('m')
            ->select('m')
            ->where('m.id IN (:ids)')
            ->setParameter('ids', $ids)
            ->getQuery()
            ->getResult();
    }

    public function getMinerByStatusPool(int $statusPool): array
    {
        return $this->entityManager
            ->getRepository($this->getEntityName())
            ->createQueryBuilder('m')
            ->select('m', 'cr')
            ->join('m.credential', 'cr')
            ->where('m.statusPool = :statusPool')
            ->setParameter('statusPool', $statusPool)
            ->getQuery()
            ->getResult();
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
     * @param int $id
     * @return Miner|null
     * @throws NonUniqueResultException
     */
    public function getMinerAndPools(int $id): ?Miner
    {
        return $this->entityManager
            ->getRepository($this->getEntityName())
            ->createQueryBuilder('m')
            ->select('m', 'cr', 'p', 'c')
            ->join('m.credential', 'cr')
            ->leftJoin('m.pools', 'p')
            ->leftJoin('m.config', 'c')
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
     * @param array $miners
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function saveAll(array $miners): void
    {
        if (count($miners) > 0) {
            foreach ($miners as $miner) {
                $this->entityManager->persist($miner);
            }

            $this->entityManager->flush();
        }
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