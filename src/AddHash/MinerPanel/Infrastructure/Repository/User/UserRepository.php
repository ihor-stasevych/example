<?php

namespace App\AddHash\MinerPanel\Infrastructure\Repository\User;

use Doctrine\ORM\ORMException;
use Doctrine\ORM\OptimisticLockException;
use App\AddHash\MinerPanel\Domain\User\User;
use App\AddHash\MinerPanel\Domain\User\UserRepositoryInterface;
use App\AddHash\System\GlobalContext\Repository\AbstractRepository;

class UserRepository extends AbstractRepository implements UserRepositoryInterface
{
    /**
     * @param int $id
     * @return User|null
     */
    public function get(int $id): ?User
    {
        /** @var User $user */
        $user = $this->entityRepository->find($id);

        return $user;
    }

    /**
     * @param User $user
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function save(User $user): void
    {
        $this->entityManager->persist($user);
        $this->entityManager->flush();
    }

    protected function getEntityName(): string
    {
        return User::class;
    }
}