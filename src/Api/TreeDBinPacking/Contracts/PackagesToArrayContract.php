<?php

declare(strict_types = 1);

namespace App\Api\TreeDBinPacking\Contracts;

/**
 * @phpstan-type TPackages list<array{id: int, w: float, h: float, d: float, max_wg: float}>
 */
interface PackagesToArrayContract
{
	/**
	 * @return TPackages
	 */
	public function transform(): array;
}
