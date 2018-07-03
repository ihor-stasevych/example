<?php

namespace App\AddHash\Authentication\Infrastructure\Repository;

use App\AddHash\Authentication\Infrastructure\User;
use App\AddHash\System\GlobalContext\ValueObject\Email;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Query;

class UserRepository implements UserRepositoryInterface
{
	private $entityManager;

	public function __construct(EntityManager $entityManager)
	{
		$this->entityManager = $entityManager;
	}

	public function getByEmail(Email $email): ?User
	{
		$user = $this->entityManager->getRepository(User::class);

		$res = $user->createQueryBuilder('u')
			->select('u')
			->where('email = ?1')
			->setParameter(1, $email)
			->getQuery()
			->getResult(Query::HYDRATE_ARRAY);

		var_dump($res);

		if (!$res) {
			return null;
		}



		return $res;
	}
}