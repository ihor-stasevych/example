<?php
namespace App\AddHash\AdminPanel\Infrastructure\Repository\User;

use App\AddHash\AdminPanel\Domain\User\Exceptions\UserRegisterEmailExistException;
use App\AddHash\AdminPanel\Domain\User\User;
use App\AddHash\AdminPanel\Domain\User\UserRepositoryInterface;
use App\AddHash\System\GlobalContext\Identity\UserId;
use App\AddHash\System\GlobalContext\ValueObject\Email;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\ORMException;
use Doctrine\ORM\ORMInvalidArgumentException;

class UserRepository implements UserRepositoryInterface
{
	protected $entityManager;

	public function __construct(EntityManager $entityManager)
	{
		$this->entityManager = $entityManager;
	}

	/***
	 * @param UserId $id
	 * @return User|null
	 */
	public function getById(UserId $id): ?User
	{
		return $this->entityManager->getRepository(User::class)->find($id);
	}

	/**
	 * @param Email $email
	 * @throws NonUniqueResultException
	 * @return User|null
	 */
	public function getByEmail(Email $email): ?User
	{
		$user = $this->entityManager->getRepository(User::class);

		$res = $user->createQueryBuilder('u')
			->select('u')
			->where('u.email = :email')
			->setParameter('email', $email->getEmail())
			->getQuery();

		return $res->getOneOrNullResult();
	}

	/**
	 * @param User $user
	 * @throws ORMInvalidArgumentException
	 * @throws ORMException
	 */
	public function create(User $user)
	{
		$this->entityManager->persist($user);
		$this->entityManager->flush($user);
	}

    /**
     * @return mixed|void
     * @throws ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
	public function update()
	{
        $this->entityManager->flush();
	}

	/**
	 * @param string $username
	 * @return User|null
	 * @throws NonUniqueResultException
	 */
	public function getByUserName(string $username): ?User
	{
		$user = $this->entityManager->getRepository(User::class);

		$res = $user->createQueryBuilder('u')
			->select('u')
			->where('u.userName = :userName')
			->setParameter('userName', $username)
			->getQuery();

		return $res->getOneOrNullResult();
	}

    /**
     * @param Email $email
     * @param string $userName
     * @return User|null
     * @throws NonUniqueResultException
     */
    public function getByEmailOrUserName(Email $email, string $userName): ?User
    {
        $user = $this->entityManager->getRepository(User::class);

        $res = $user->createQueryBuilder('u')
            ->select('u')
            ->where('u.email = :email')
            ->orWhere('u.userName = :userName')
            ->setParameter('email', $email->getEmail())
            ->setParameter('userName', $userName)
            ->getQuery();

        return $res->getOneOrNullResult();
    }
}