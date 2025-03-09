<?php

declare(strict_types = 1);

namespace App\Api\TreeDBinPacking\Caching;

use App\Api\TreeDBinPacking\Contracts\PackagesToArrayContract;
use Nette\Utils\DateTime;
use Psr\SimpleCache\CacheInterface;

/**
 * @phpstan-import-type TPackages from PackagesToArrayContract
 */
final readonly class PackagesToArrayCache
{
	public function __construct(private PackagesToArrayContract $packagesToArray, private CacheInterface $cache) {}

	/**
	 * @return TPackages
	 */
	public function execute(): array
	{
		$key = 'packages.to.array';
		/** @var TPackages|null $data */
		$data = $this->cache->get($key);

		if ($data === null) {
			$data = $this->packagesToArray->transform();
			$this->cache->set($key, $data, DateTime::HOUR);
		}

		return $data;
	}
}
