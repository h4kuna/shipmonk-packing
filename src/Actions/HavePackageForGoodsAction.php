<?php

declare(strict_types = 1);

namespace App\Actions;

use App\Contracts\FindPackagingQueryContract;
use App\Entity\Packaging;
use App\Goods\ItemsCollection;

final readonly class HavePackageForGoodsAction
{
	public function __construct(private FindPackagingQueryContract $findPackagingQuery) {}

	public function execute(ItemsCollection $items): ?Packaging // @phpstan-ignore-line
	{
		// todo compare with input, fallback not implemented
		return $this->findPackagingQuery->execute(5);
	}
}
