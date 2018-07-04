<?php

namespace App\AddHash\AdminPanel\Infrastructure\Repository\Store\Category;

use App\AddHash\AdminPanel\Domain\Store\Category\CategoryRepositoryInterface;
use App\AddHash\AdminPanel\Domain\Store\Category\Model\Store_Category;
use App\AddHash\AdminPanel\Domain\Store\Category\Model\StoreCategory;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Query;
use Pagerfanta\Adapter\DoctrineORMAdapter;
use Pagerfanta\Pagerfanta;

class StoreCategoryDQLRepository implements CategoryRepositoryInterface
{
	protected $entityManager;

	public function __construct(EntityManager $entityManager)
	{
		$this->entityManager = $entityManager;
	}

	public function findById($id): ?StoreCategory
	{

		$res =  $this->entityManager->getRepository(StoreCategory::class)
			->createQueryBuilder('e')
			->select('e')
			->where('e.id = :id')
			->setParameter('id', $id)
			->getQuery();


		return $res->getOneOrNullResult();
	}

	public function create(StoreCategory $category)
	{
		// TODO: Implement create() method.
	}

	public function update(StoreCategory $category)
	{
		// TODO: Implement update() method.
	}

	public function list()
	{
		$storeCategories = $this->entityManager->getRepository(StoreCategory::class);

		$res = $storeCategories
			->createQueryBuilder('e')
			->select('e')
			->getQuery()
			->getResult(Query::HYDRATE_ARRAY);


		return $res;


		//Just for test
		/**
		$doctrineAdapter = new DoctrineORMAdapter($res);
		$paginator = new Pagerfanta($doctrineAdapter);
		$paginator->setCurrentPage(1);
		$paginator->setMaxPerPage(1);

		var_dump($paginator->getCurrentPageResults(),
			$paginator->getNbResults(),
			$paginator->getNbPages(),
			$paginator->getCurrentPage(),
			$paginator->getMaxPerPage());


		*/

	}
}