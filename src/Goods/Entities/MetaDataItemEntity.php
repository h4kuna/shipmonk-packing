<?php

declare(strict_types = 1);

namespace App\Goods\Entities;

final class MetaDataItemEntity
{
	public function __construct(
		public float $volume,
		public float $height,
		public float $width,
		public float $length,
		public float $weight,
	) {}
}
