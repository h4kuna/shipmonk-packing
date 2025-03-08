<?php

declare(strict_types = 1);

namespace App\Api\TreeDBinPacking\Contracts;

use App\Entity\Packaging;
use Generator;

interface GetPackagesQueryContract
{
	/**
	 * @return Generator<int, Packaging>
	 */
	public function execute(): Generator;
}
