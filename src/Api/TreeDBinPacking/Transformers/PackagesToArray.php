<?php

declare(strict_types = 1);

namespace App\Api\TreeDBinPacking\Transformers;

use App\Api\TreeDBinPacking\Contracts\GetPackagesQueryContract;
use App\Api\TreeDBinPacking\Contracts\PackagesToArrayContract;

final readonly class PackagesToArray implements PackagesToArrayContract
{
	public function __construct(private GetPackagesQueryContract $getPackagesQuery) {}

	public function transform(): array
	{
		$bins = [];
		foreach ($this->getPackagesQuery->execute() as $package) {
			$bins[] = [
				'id' => $package->getId(),
				'w' => $package->getWidth(),
				'h' => $package->getHeight(),
				'd' => $package->getLength(),
				'wg' => $package->getMaxWeight(),
				'max_wg' => $package->getMaxWeight(),
				'type' => 'box',
			];
		}

		return $bins;
	}
}
