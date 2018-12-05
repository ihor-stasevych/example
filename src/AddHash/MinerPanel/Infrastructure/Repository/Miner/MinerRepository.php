<?php

namespace App\AddHash\MinerPanel\Infrastructure\Repository\Miner;

use Doctrine\ORM\ORMException;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\NonUniqueResultException;
use App\AddHash\MinerPanel\Domain\User\Model\User;
use App\AddHash\MinerPanel\Domain\Miner\Model\Miner;
use App\AddHash\System\GlobalContext\Repository\AbstractRepository;
use App\AddHash\MinerPanel\Domain\Miner\Repository\MinerRepositoryInterface;

class MinerRepository extends AbstractRepository implements MinerRepositoryInterface
{
    public function getMinersByUser(User $user): array
    {
        return $this->entityManager
            ->getRepository($this->getEntityName())
            ->createQueryBuilder('m')
            ->select('m', 't', 'a')
            ->join('m.type', 't')
            ->join('m.algorithm', 'a')
            ->where('m.user = :user')
            ->setParameter('user', $user)
            ->getQuery()
            ->getResult();
    }

    /**
     * @param int $id
     * @param User $user
     * @return Miner
     * @throws NonUniqueResultException
     */
    public function getMinerByIdAndUser(int $id, User $user): ?Miner
    {
        return $this->entityManager
            ->getRepository($this->getEntityName())
            ->createQueryBuilder('m')
            ->select('m', 't', 'a')
            ->join('m.type', 't')
            ->join('m.algorithm', 'a')
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
            ->select('m', 't', 'a')
            ->join('m.type', 't')
            ->join('m.algorithm', 'a')
            ->where('m.title = :title')
            ->andWhere('m.user = :user')
            ->setParameter('title', $title)
            ->setParameter('user', $user)
            ->getQuery()
            ->getOneOrNullResult();
    }

    public function get(int $id): ?Miner
    {
        /** @var Miner $miner */
        $miner = $this->entityRepository->find($id);

        return $miner;
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

    protected function getEntityName(): string
    {
        return Miner::class;
    }
}