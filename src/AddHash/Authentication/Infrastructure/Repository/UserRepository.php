<?php

namespace App\AddHash\Authentication\Infrastructure\Repository;

use Doctrine\ORM\ORMException;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\NonUniqueResultException;
use App\AddHash\Authentication\Domain\Model\User;
use App\AddHash\System\GlobalContext\ValueObject\Email;
use App\AddHash\System\GlobalContext\Repository\AbstractRepository;
use App\AddHash\Authentication\Domain\Repository\UserRepositoryInterface;

class UserRepository extends AbstractRepository implements UserRepositoryInterface
{
    /**
     * @param Email $email
     * @return User|null
     * @throws NonUniqueResultException
     */
    public function getByEmail(Email $email): ?User
    {
        return $this->entityRepository->createQueryBuilder('u')
            ->select('u')
            ->where('u.email.email = :email')
            ->setParameter('email', $email->getEmail())
            ->getQuery()
            ->getOneOrNullResult();
    }

    /**
     * @param User $user
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function save(User $user)
    {
        $this->entityManager->persist($user);
        $this->entityManager->flush($user);
    }

    /**
     * @return string
     */
    protected function getEntityName()
    {
        return User::class;
    }
}