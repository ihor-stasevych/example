<?php
namespace App\AddHash\AdminPanel\Infrastructure\Repository\User;

use Doctrine\ORM\ORMException;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\NonUniqueResultException;
use App\AddHash\AdminPanel\Domain\User\UserWallet;
use App\AddHash\System\GlobalContext\Repository\AbstractRepository;
use App\AddHash\AdminPanel\Domain\User\UserWalletRepositoryInterface;

class UserWalletRepository extends AbstractRepository implements UserWalletRepositoryInterface
{
    /**
     * @param array $ids
     * @param int $userId
     * @return array
     */
    public function getByIdsAndUserId(array $ids, int $userId): array
    {
        return $this->entityManager->getRepository($this->getEntityName())
            ->createQueryBuilder('uw')
            ->select('uw')
            ->andWhere('uw.id IN (:ids)')
            ->andWhere('uw.user = :userId')
            ->setParameter('ids', $ids)
            ->setParameter('userId', $userId)
            ->getQuery()
            ->getResult();
    }

    /**
     * @param array $ids
     * @param int $typeId
     * @param string $value
     * @return array|null
     * @throws NonUniqueResultException
     */
    public function getByUnique(array $ids, int $typeId, string $value): ?UserWallet
    {
        return $this->entityManager->getRepository($this->getEntityName())
            ->createQueryBuilder('uw')
            ->select('uw')
            ->join('uw.wallet', 'w')
            ->andWhere('uw.id NOT IN (:ids)')
            ->andWhere('w.value = :value')
            ->andWhere('w.type = :type')
            ->setParameter('ids', $ids)
            ->setParameter('value', $value)
            ->setParameter('type', $typeId)
            ->getQuery()
            ->getOneOrNullResult();
    }

    /**
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function update()
    {
        $this->entityManager->flush();
    }

    /**
     * @param UserWallet $userWallet
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function create(UserWallet $userWallet)
    {
        $this->entityManager->persist($userWallet);
        $this->entityManager->flush($userWallet);
    }

    /**
     * @return string
     */
    protected function getEntityName()
    {
        return UserWallet::class;
    }
}