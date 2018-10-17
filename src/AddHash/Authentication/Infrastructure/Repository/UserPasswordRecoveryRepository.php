<?php

namespace App\AddHash\Authentication\Infrastructure\Repository;

use Doctrine\ORM\ORMException;
use Doctrine\ORM\OptimisticLockException;
use App\AddHash\Authentication\Domain\Model\User;
use App\AddHash\Authentication\Domain\Model\UserPasswordRecovery;
use App\AddHash\System\GlobalContext\Repository\AbstractRepository;
use App\AddHash\Authentication\Domain\Repository\UserPasswordRecoveryRepositoryInterface;

class UserPasswordRecoveryRepository extends AbstractRepository implements UserPasswordRecoveryRepositoryInterface
{
    /**
     * @param User $user
     * @return object|null
     */
    public function findByUser(User $user): ?UserPasswordRecovery
    {
        return $this->entityRepository->findOneBy(['user' => $user->getId()]);
    }

    /**
     * @param null|string $hash
     * @return null|object
     */
    public function findByHash(?string $hash): ?UserPasswordRecovery
    {
        return $this->entityRepository->findOneBy(['hash' => $hash]);
    }

    /**
     * @param UserPasswordRecovery $passwordRecovery
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function remove(UserPasswordRecovery $passwordRecovery)
    {
        $this->entityManager->remove($passwordRecovery);
        $this->entityManager->flush();
    }

    /**
     * @param UserPasswordRecovery $passwordRecovery
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function save(UserPasswordRecovery $passwordRecovery)
    {
        $this->entityManager->persist($passwordRecovery);
        $this->entityManager->flush();
    }

    protected function getEntityName()
    {
        return UserPasswordRecovery::class;
    }

}