<?php

namespace App\AddHash\MinerPanel\Infrastructure\Repository\Rig;

use Pagerfanta\Pagerfanta;
use App\AddHash\MinerPanel\Domain\Rig\Rig;
use Pagerfanta\Adapter\DoctrineORMAdapter;
use Doctrine\ORM\NonUniqueResultException;
use App\AddHash\MinerPanel\Domain\User\User;
use App\AddHash\MinerPanel\Domain\Rig\RigRepositoryInterface;
use App\AddHash\System\GlobalContext\Repository\AbstractRepository;

class RigRepository extends AbstractRepository implements RigRepositoryInterface
{
    public function getRigsByUser(User $user, ?int $currentPage): ?Pagerfanta
    {
        $result = $this->entityManager
            ->getRepository($this->getEntityName())
            ->createQueryBuilder('r')
            ->select('r', 'm', 'a', 't')
            ->leftJoin('r.miners', 'm')
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
            ->select('r', 'm', 'a', 't')
            ->leftJoin('r.miners', 'm')
            ->leftJoin('m.algorithm', 'a')
            ->leftJoin('m.type', 't')
            ->where('r.id = :id')
            ->andWhere('r.user = :user')
            ->setParameter('id', $id)
            ->setParameter('user', $user)
            ->getQuery()
            ->getOneOrNullResult();
    }

    protected function getEntityName(): string
    {
        return Rig::class;
    }
}