<?php
namespace App\AddHash\AdminPanel\Customer\CustomerContext\Infrastructure\Repository;

use App\AddHash\AdminPanel\Customer\CustomerContext\Domain\Model\Customer\User;
use App\AddHash\AdminPanel\Customer\CustomerContext\Domain\Model\Customer\UserRepositoryInterface;
use App\AddHash\System\GlobalContext\Identity\UserId;
use App\AddHash\System\GlobalContext\ValueObject\Email;
use Doctrine\ORM\EntityManager;

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
		// TODO: Implement getByEmail() method.
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