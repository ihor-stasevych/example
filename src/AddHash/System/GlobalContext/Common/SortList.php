<?php

namespace App\AddHash\System\GlobalContext\Common;

abstract class SortList
{
	const ORDER_ASC = 'asc';
	const ORDER_DESC = 'desc';

	const ORDERS = [
		self::ORDER_ASC,
		self::ORDER_DESC,
	];

	const SORTED_FIELDS = [];

	protected $sort;

	protected $order;

	/**
	 * Sort constructor.
	 * @param $sort
	 * @param $order
	 */
	public function __construct(string $sort = null, string $order = null)
	{
		$order = $order ? strtolower($order) : null;

		if (null === $order || !in_array($order, $this->getOrdersList())) {
			$order = $this->getDefaultOrder();
		}

		if (null === $sort || !in_array($sort, $this->getSortList())) {
			$sort = $this->getDefaultSort();
		}

		$this->sort = $sort;
		$this->order = $order;
	}

	abstract protected function getDefaultOrder();

	abstract protected function getDefaultSort();

	abstract protected function getOrdersList();

	abstract protected function getSortList();

	/**
	 * @return string
	 */
	public function getSort(): string
	{
		return $this->sort;
	}

	/**
	 * @return string
	 */
	public function getOrder(): string
	{
		return $this->order;
	}
}