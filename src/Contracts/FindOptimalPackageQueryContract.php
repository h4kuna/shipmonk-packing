<?php

declare(strict_types = 1);

namespace App\Contracts;

use App\Entity\Packaging;
use App\Goods\Entities\MetaDataItemEntity;

interface FindOptimalPackageQueryContract
{
	public function execute(MetaDataItemEntity $metaDataItem): ?Packaging;
}
