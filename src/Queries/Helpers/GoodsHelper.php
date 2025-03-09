<?php

declare(strict_types = 1);

namespace App\Queries\Helpers;

use App\Goods\Collections\ItemsCollection;
use Doctrine\ORM\QueryBuilder;

final class GoodsHelper
{
	public static function dimensionCondition(ItemsCollection $items, QueryBuilder $queryBuilder): QueryBuilder
	{
		$orExpressions = [];
		foreach ($items as $i => $item) {
			$hKey = "height_$i";
			$wKey = "width_$i";
			$lKey = "length_$i";

			$orExpressions[] = $queryBuilder->expr()->andX(
				$queryBuilder->expr()->eq('p.height', ":$hKey"),
				$queryBuilder->expr()->eq('p.width', ":$wKey"),
				$queryBuilder->expr()->eq('p.length', ":$lKey"),
			);

			$queryBuilder->setParameter($hKey, $item->height)
				->setParameter($wKey, $item->width)
				->setParameter($lKey, $item->length);
		}

		$queryBuilder->andWhere($queryBuilder->expr()->orX(...$orExpressions));

		return $queryBuilder;
	}
}
