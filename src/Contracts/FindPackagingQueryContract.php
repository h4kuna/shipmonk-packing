<?php

declare(strict_types = 1);

namespace App\Contracts;

use App\Entity\Packaging;

interface FindPackagingQueryContract
{
	public function execute(int $packagingId): Packaging;
}
