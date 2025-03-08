<?php

declare(strict_types = 1);

namespace App\Api\TreeDBinPacking;

use App\Api\TreeDBinPacking\Exceptions\CouldNotPackToOnePackageException;
use App\Api\TreeDBinPacking\Exceptions\TreeDBinPackingResponseErrorsException;
use App\Api\TreeDBinPacking\Helpers\ErrorMessage;

/**
 * @phpstan-import-type TypeResponse from Client
 */
final class CheckResponseForErrors
{
	/**
	 * @param TypeResponse $responseData
	 */
	public function execute(array $responseData): void
	{
		if ($responseData['response']['errors'] !== []) {
			if ($responseData['response']['not_packed_items'] !== []) {
				throw new CouldNotPackToOnePackageException(ErrorMessage::join($responseData['response']['errors']));
			}

			throw new TreeDBinPackingResponseErrorsException(
				$responseData['response']['errors'],
				$responseData['response']['status'],
			);
		}
	}
}
