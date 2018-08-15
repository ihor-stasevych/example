<?php
namespace App\AddHash\AdminPanel\Infrastructure\Repository\User;

use Doctrine\ORM\ORMException;
use Doctrine\ORM\OptimisticLockException;
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
        $user = $this->entityManager->getRepository($this->getEntityName());

        $res = $user->createQueryBuilder('uw')
            ->select('uw')
            ->andWhere('uw.id IN (:ids)')
            ->andWhere('uw.user = :userId')
            ->setParameter('ids', $ids)
            ->setParameter('userId', $userId)
            ->getQuery();

        return $res->getResult();
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