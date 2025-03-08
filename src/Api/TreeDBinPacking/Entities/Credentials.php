<?php

declare(strict_types = 1);

namespace App\Api\TreeDBinPacking\Entities;

final readonly class Credentials
{
	public function __construct(
		public string $username,
		public string $apiKey,
	) {}
}
