<?php

declare(strict_types = 1);

namespace App\Goods\Factories;

use App\Goods\Collections\ItemsCollection;
use App\Goods\Entities\MetaDataItemEntity;

final readonly class MetaDataItemFactory
{
	public function create(ItemsCollection $items): MetaDataItemEntity
	{
		$weight = $maxLength = $maxWidth = $maxHeight = $volume = 0;
		foreach ($items as $item) {
			$volume += $item->width * $item->height * $item->length;
			$maxLength = max($maxLength, $item->length);
			$maxWidth = max($maxWidth, $item->width);
			$maxHeight = max($maxHeight, $item->height);
			$weight += $item->weight;
		}

		return new MetaDataItemEntity($volume, $maxHeight, $maxWidth, $maxLength, $weight);
	}

}
