<?php

namespace App\AddHash\MinerPanel\Infrastructure\Repository\Rig;

use Pagerfanta\Pagerfanta;
use Doctrine\ORM\ORMException;
use Doctrine\ORM\OptimisticLockException;
use App\AddHash\MinerPanel\Domain\Rig\Rig;
use Pagerfanta\Adapter\DoctrineORMAdapter;
use Doctrine\ORM\NonUniqueResultException;
use App\AddHash\MinerPanel\Domain\User\User;
use App\AddHash\MinerPanel\Domain\Rig\RigRepositoryInterface;
use App\AddHash\System\GlobalContext\Repository\AbstractRepository;

class RigRepository extends AbstractRepository implements RigRepositoryInterface
{
    public function getRigsByUserWithPagination(User $user, ?int $currentPage): Pagerfanta
    {
        $result = $this->entityManager
            ->getRepository($this->getEntityName())
            ->createQueryBuilder('r')
            ->select('r', 'm', 'cr', 'a', 't')
            ->leftJoin('r.miners', 'm')
            ->leftJoin('m.credential', 'cr')
            ->leftJoin('m.algorithm', 'a')
            ->leftJoin('m.type', 't')
            ->where('r.user = :user')
            ->setParameter('user', $user)
            ->getQuery();

        $pager = new Pagerfanta(
            new DoctrineORMAdapter($result)
        );

        $pager->setMaxPerPage(Rig::MAX_PER_PAGE);
        $pager->setCurrentPage($currentPage ?? 1);

        return $pager;
    }

    public function getRigsByUser(User $user): array
    {
        return $this->entityManager
            ->getRepository($this->getEntityName())
            ->createQueryBuilder('r')
            ->select('r')
            ->where('r.user = :user')
            ->setParameter('user', $user)
            ->getQuery()
            ->getResult();
    }

    /**
     * @param int $id
     * @param User $user
     * @return Rig|null
     * @throws NonUniqueResultException
     */
    public function getRigByIdAndUser(int $id, User $user): ?Rig
    {
        return $this->entityManager
            ->getRepository($this->getEntityName())
            ->createQueryBuilder('r')
            ->select('r', 'm', 'cr', 'a', 't')
            ->leftJoin('r.miners', 'm')
            ->leftJoin('m.credential', 'cr')
            ->leftJoin('m.algorithm', 'a')
            ->leftJoin('m.type', 't')
            ->where('r.id = :id')
            ->andWhere('r.user = :user')
            ->setParameter('id', $id)
            ->setParameter('user', $user)
            ->getQuery()
            ->getOneOrNullResult();
    }

    /**
     * @param int $id
     * @return Rig|null
     * @throws NonUniqueResultException
     */
    public function getRigById(int $id): ?Rig
    {
        return $this->entityManager
            ->getRepository($this->getEntityName())
            ->createQueryBuilder('r')
            ->select('r', 'm', 'cr', 'a', 't')
            ->leftJoin('r.miners', 'm')
            ->leftJoin('m.credential', 'cr')
            ->leftJoin('m.algorithm', 'a')
            ->leftJoin('m.type', 't')
            ->where('r.id = :id')
            ->setParameter('id', $id)
            ->getQuery()
            ->getOneOrNullResult();
    }

    /**
     * @param int $id
     * @param User $user
     * @return Rig|null
     * @throws NonUniqueResultException
     */
    public function existRigByIdAndUser(int $id, User $user): ?Rig
    {
        return $this->entityManager
            ->getRepository($this->getEntityName())
            ->createQueryBuilder('r')
            ->select('r', 'm')
            ->leftJoin('r.miners', 'm')
            ->where('r.id = :id')
            ->andWhere('r.user = :user')
            ->setParameter('id', $id)
            ->setParameter('user', $user)
            ->getQuery()
            ->getOneOrNullResult();
    }

    /**
     * @param string $title
     * @param User $user
     * @return Rig|null
     * @throws NonUniqueResultException
     */
    public function getRigByTitleAndUser(string $title, User $user): ?Rig
    {
        return $this->entityManager
            ->getRepository($this->getEntityName())
            ->createQueryBuilder('r')
            ->select('r')
            ->where('r.title = :title')
            ->andWhere('r.user = :user')
            ->setParameter('title', $title)
            ->setParameter('user', $user)
            ->getQuery()
            ->getOneOrNullResult();
    }

    /**
     * @param Rig $rig
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function save(Rig $rig): void
    {
        $this->entityManager->persist($rig);
        $this->entityManager->flush();
    }

    /**
     * @param Rig $rig
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function delete(Rig $rig): void
    {
        $this->entityManager->remove($rig);
        $this->entityManager->flush();
    }

    protected function getEntityName(): string
    {
        return Rig::class;
    }
}