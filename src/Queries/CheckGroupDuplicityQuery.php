<?php

declare(strict_types = 1);

namespace App\Queries;

use App\Entity\Goods;
use App\Entity\Groups;
use App\Entity\PackagingGroupGood;
use App\Goods\ItemsCollection;
use App\Queries\Helpers\GoodsHelper;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Query\Expr\Join;
use Psr\Log\LoggerInterface;

final readonly class CheckGroupDuplicityQuery
{
	public function __construct(private EntityManagerInterface $entityManager, private LoggerInterface $logger) {}

	public function execute(ItemsCollection $items, int $packagingId): bool
	{
		$queryBuilder = $this->entityManager->createQueryBuilder()
			->from(Goods::class, 'p')
			->join(PackagingGroupGood::class, 'pgg', Join::WITH, 'p.id = pgg.goods')
			->join(Groups::class, 'g', Join::WITH, 'g.id = pgg.group AND g.count = :count')
			->where('pgg.packaging = :packaging_id')
			->setParameter('packaging_id', $packagingId)
			->setParameter('count', count($items))
			->select('g')
			->groupBy('g.id')
			->having('g.count = COUNT(1)');

		$query = GoodsHelper::dimensionCondition($items, $queryBuilder)->getQuery();

		/** @var list<Goods> $results */
		$results = $query->execute();

		$groups = [];
		foreach ($results as $group) {
			$groups[] = $group;
		}

		if ($groups === []) {
			return false;
		} elseif (count($groups) > 1) {
			$this->logger->warning(
				'Too many rows for query. Should return one record.',
				[
					'sql' => $query->getSQL(),
					'parameter' => $query->getParameters(),
				],
			);
		}

		return true;
	}
}
