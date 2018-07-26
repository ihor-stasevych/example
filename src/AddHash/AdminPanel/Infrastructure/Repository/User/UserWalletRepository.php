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
     * @param UserWallet $userWallet
     * @throws ORMException
     * @throws OptimisticLockException
     */
	public function create(UserWallet $userWallet)
	{
		$this->entityManager->persist($userWallet);
		$this->entityManager->flush($userWallet);
	}

    public function deleteByUserId(int $userId): int
    {
        return $this->entityRepository->createQueryBuilder('w')
            ->delete($this->getEntityName(), 'w')
            ->where('w.userId = :userId')
            ->setParameter('userId', $userId)
            ->getQuery()
            ->execute();
    }

    /**
     * @return string
     */
    protected function getEntityName()
    {
        return UserWallet::class;
    }
}