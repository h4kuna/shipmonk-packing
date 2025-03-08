<?php

declare(strict_types = 1);

namespace App\Goods;

final class ItemEntity
{
	public function __construct(
		public float $height,
		public float $width,
		public float $length,
		public float $weight,
	) {}
}
