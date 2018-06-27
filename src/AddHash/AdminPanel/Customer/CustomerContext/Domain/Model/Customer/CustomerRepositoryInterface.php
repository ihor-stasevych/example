<?php

namespace App\AddHash\AdminPanel\Customer\CustomerContext\Domain\Model\Customer;

use App\AddHash\System\GlobalContext\Identity\CustomerId;
use App\AddHash\System\GlobalContext\ValueObject\Email;

interface CustomerRepositoryInterface
{
	/***
	 * @param CustomerId $id
	 * @return Customer|null
	 */
	public function getById(CustomerId $id): ?Customer;

	/**
	 * @param Email $email
	 * @return Customer|null
	 */
	public function getByEmail(Email $email): ?Customer;

	/**
	 * @param Customer $customer
	 * @return mixed
	 */
	public function create(Customer $customer);

	/**
	 * @param Customer $customer
	 * @return mixed
	 */
	public function update(Customer $customer);
}