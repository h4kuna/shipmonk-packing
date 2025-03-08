<?php

declare(strict_types = 1);

namespace App\Goods\Actions;

use App\Goods\ItemEntity;

final class SortItemFieldsAction
{
	public function execute(ItemEntity $item): ItemEntity
	{
		$max = max($item->height, $item->width, $item->length);
		$min = min($item->height, $item->width, $item->length);

		return new ItemEntity(
			$max,
			self::useMiddle($item, $max, $min),
			$min,
			$item->weight,
		);
	}

	private static function useMiddle(ItemEntity $item, float $max, float $min): float
	{
		if ($item->width === $max && $item->height === $min || $item->height === $max && $item->width === $min) {
			return $item->length;
		} elseif ($item->width === $max && $item->length === $min || $item->length === $max && $item->width === $min) {
			return $item->height;
		}

		return $item->width;
	}
}
