<?php
namespace App\AddHash\AdminPanel\Infrastructure\Repository\User;

use App\AddHash\AdminPanel\Domain\User\User;
use App\AddHash\AdminPanel\Domain\User\UserRepositoryInterface;
use App\AddHash\System\GlobalContext\Identity\UserId;
use App\AddHash\System\GlobalContext\ValueObject\Email;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Query;

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
	 * @param User $customer
	 * @return mixed
	 */
	public function create(User $customer)
	{
		// TODO: Implement create() method.
	}

	/**
	 * @param User $customer
	 * @return mixed
	 */
	public function update(User $customer)
	{
		// TODO: Implement update() method.
	}
}