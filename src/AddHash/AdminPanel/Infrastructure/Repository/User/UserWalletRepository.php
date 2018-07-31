<?php
namespace App\AddHash\AdminPanel\Infrastructure\Repository\User;

use Doctrine\ORM\ORMException;
use Doctrine\ORM\OptimisticLockException;
use App\AddHash\AdminPanel\Domain\User\UserWallet;
use App\AddHash\System\GlobalContext\Repository\AbstractRepository;
use App\AddHash\AdminPanel\Domain\User\UserWalletRepositoryInterface;

class UserWalletRepository extends AbstractRepository implements UserWalletRepositoryInterface
{
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

    public function getByValueAndWalletIdAndUserId(string $value, int $walletId, int $userId): ?UserWallet
    {
        $user = $this->entityManager->getRepository($this->getEntityName());

        $res = $user->createQueryBuilder('uw')
            ->select('uw')
            ->andWhere('uw.value = :value')
            ->andWhere('uw.wallet = :walletId')
            ->andWhere('uw.user = :userId')
            ->setParameter('value', $value)
            ->setParameter('walletId', $walletId)
            ->setParameter('userId', $userId)
            ->getQuery();

        return $res->getOneOrNullResult();
    }

    /**
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function update()
    {
        $this->entityManager->flush();
    }

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