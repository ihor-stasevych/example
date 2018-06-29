<?php
namespace App\AddHash\AdminPanel\Customer\CustomerContext\Infrastructure\Repository;

use App\AddHash\AdminPanel\Customer\CustomerContext\Domain\Model\Customer\Customer;
use App\AddHash\AdminPanel\Customer\CustomerContext\Domain\Model\Customer\CustomerRepositoryInterface;
use App\AddHash\System\GlobalContext\Identity\CustomerId;
use App\AddHash\System\GlobalContext\ValueObject\Email;
use Doctrine\ORM\EntityManager;

class CustomerRepository implements CustomerRepositoryInterface
{
	protected $entityManager;

	public function __construct(EntityManager $entityManager)
	{
		$this->entityManager = $entityManager;
	}

	/***
	 * @param CustomerId $id
	 * @return Customer|null
	 */
	public function getById(CustomerId $id): ?Customer
	{
		return $this->entityManager->getRepository(Customer::class)->find($id);
	}

	/**
	 * @param Email $email
	 * @return Customer|null
	 */
	public function getByEmail(Email $email): ?Customer
	{
		// TODO: Implement getByEmail() method.
	}

	/**
	 * @param Customer $customer
	 * @return mixed
	 */
	public function create(Customer $customer)
	{
		// TODO: Implement create() method.
	}

	/**
	 * @param Customer $customer
	 * @return mixed
	 */
	public function update(Customer $customer)
	{
		// TODO: Implement update() method.
	}
}