<?php

declare(strict_types = 1);

namespace App\Queries;

use App\Entity\Goods;
use App\Goods\Collections\ItemsCollection;
use App\Queries\Helpers\GoodsHelper;
use Doctrine\ORM\EntityManagerInterface;

final readonly class FindExistingGoodsQuery
{
	public function __construct(private EntityManagerInterface $entityManager) {}

	/**
	 * @return array<string, Goods>
	 */
	public function execute(ItemsCollection $items): array
	{
		$queryBuilder = $this->entityManager->createQueryBuilder()
			->from(Goods::class, 'p')
			->select('p');

		$query = GoodsHelper::dimensionCondition($items, $queryBuilder)->getQuery();

		/** @var list<Goods> $results */
		$results = $query->execute();

		$goods = [];
		foreach ($results as $good) {
			$goods[$good->getKey()] = $good;
		}

		return $goods;
	}
}
