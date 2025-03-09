<?php

declare(strict_types = 1);

namespace App\Goods\Actions;

use App\Queries\FindMaxPackageWeightQuery;
use Nette\Utils\DateTime;
use Psr\SimpleCache\CacheInterface;

final readonly class FindMaxPackageWeightAction
{
	public function __construct(
		private FindMaxPackageWeightQuery $findMaxPackageWeightQuery,
		private CacheInterface $cache,
	) {}

	public function execute(): float
	{
		$key = 'packaging.max.weight';
		/** @var float|null $maxWeight */
		$maxWeight = $this->cache->get($key);
		if ($maxWeight === null) {
			$maxWeight = $this->findMaxPackageWeightQuery->execute();
			$this->cache->set($key, $maxWeight, DateTime::DAY);
		}

		return $maxWeight;
	}
}
