<?php

declare(strict_types = 1);

namespace App\Api\TreeDBinPacking\Factories;

use GuzzleHttp\Psr7\Uri;
use Psr\Http\Message\UriFactoryInterface;
use Psr\Http\Message\UriInterface;

final readonly class UriFactory
{
	public function __construct(
		private UriFactoryInterface $uriFactory,
		private string $host = 'eu.api.3dbinpacking.com',
	) {}

	public function createUri(string $uri = ''): UriInterface
	{
		return $this->uriFactory->createUri('https://' . $this->host . $uri);
	}
}
