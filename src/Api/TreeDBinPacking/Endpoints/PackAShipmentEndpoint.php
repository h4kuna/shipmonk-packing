<?php

declare(strict_types = 1);

namespace App\Api\TreeDBinPacking\Endpoints;

use App\Api\Exceptions\InvalidResponseException;
use App\Api\Exceptions\ResponseException;
use App\Api\TreeDBinPacking\Caching\PackagesToArrayCache;
use App\Api\TreeDBinPacking\Client;
use App\Api\TreeDBinPacking\Contracts\PackagesToArrayContract;
use App\Api\TreeDBinPacking\Exceptions\CouldNotPackToOnePackageException;
use App\Contracts\FindPackagingQueryContract;
use App\Entity\Packaging;
use App\Goods\Collections\ItemsCollection;
use Psr\Http\Client\ClientExceptionInterface;

final readonly class PackAShipmentEndpoint
{
	public function __construct(
		private Client $client,
		private PackagesToArrayCache $packagesToArray,
		private FindPackagingQueryContract $findPackagingQuery,
	) {}

	/**
	 * @throws ResponseException
	 * @throws ClientExceptionInterface
	 */
	public function request(ItemsCollection $items): Packaging
	{
		$response = $this->client->request(
			'/packer/packIntoMany',
			['bins' => $this->packagesToArray->execute(), 'items' => self::prepareItems($items)],
		);

		$packed = isset($response['bins_packed']) && is_array($response['bins_packed']) ? $response['bins_packed'] : [];
		$count = count($packed);
		if ($count !== 1) {
			throw new CouldNotPackToOnePackageException("Packages: $count.");
		} elseif (
			isset($packed[0]['bin_data']['id']) === false // @phpstan-ignore-line
			|| is_int($packed[0]['bin_data']['id']) === false) {
			throw new InvalidResponseException('Missing id or weight of package.');
		}

		return $this->findPackagingQuery->execute($packed[0]['bin_data']['id']);
	}

	/**
	 * @param ItemsCollection $items
	 * @return list<array{id: int, w: float, h: float, d: float, wg: float, q: int, vr: int}>
	 */
	private static function prepareItems(ItemsCollection $items): array
	{
		$out = [];
		foreach ($items as $id => $item) {
			$out[] = [
				'id' => $id,
				'w' => $item->width,
				'h' => $item->height,
				'd' => $item->length,
				'wg' => $item->weight,
				'q' => 1, // todo aggregate packages
				'vr' => 1,
			];
		}

		return $out;
	}
}
