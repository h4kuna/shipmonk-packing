<?php

declare(strict_types = 1);

namespace App\Api\TreeDBinPacking\Contracts;

interface PackagesToArrayContract
{
	/**
	 * @return list<array{id: int, w: float, h: float, d: float, max_wg: float}>
	 */
	public function transform(): array;
}
