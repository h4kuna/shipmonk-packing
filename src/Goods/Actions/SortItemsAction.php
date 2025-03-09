<?php

declare(strict_types = 1);

namespace App\Goods\Actions;

use App\Goods\Entities\ItemEntity;

final class SortItemsAction
{
	/**
	 * @param list<ItemEntity> $items
	 * @return list<ItemEntity>
	 */
	public function execute(array $items): array
	{
		usort($items, function (ItemEntity $a, ItemEntity $b) {
			if ($a->height === $b->height) {
				if ($a->width === $b->width) {
					return $b->length <=> $a->length;
				}

				return $b->width <=> $a->width;
			}

			return $b->height <=> $a->height;
		});

		return $items;
	}
}
