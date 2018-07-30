<?php
namespace App\AddHash\AdminPanel\Infrastructure\Repository\Wallet;

use Doctrine\ORM\NonUniqueResultException;
use App\AddHash\AdminPanel\Domain\Wallet\Wallet;
use App\AddHash\AdminPanel\Domain\Wallet\WalletRepositoryInterface;
use App\AddHash\System\GlobalContext\Repository\AbstractRepository;

class WalletRepository extends AbstractRepository implements WalletRepositoryInterface
{
    /**
     * @param int $id
     * @return Wallet|null
     * @throws NonUniqueResultException
     */
    public function getById(int $id): ?Wallet
    {
        $user = $this->entityManager->getRepository($this->getEntityName());

        $res = $user->createQueryBuilder('w')
            ->select('w')
            ->andWhere('w.id = :id')
            ->setParameter('id', $id)
            ->getQuery();

        return $res->getOneOrNullResult();
    }

    /**
     * @return string
     */
    protected function getEntityName()
    {
        return Wallet::class;
    }
}