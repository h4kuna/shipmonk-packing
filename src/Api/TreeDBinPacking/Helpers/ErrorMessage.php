<?php

declare(strict_types = 1);

namespace App\Api\TreeDBinPacking\Helpers;

use App\Api\TreeDBinPacking\Client;

/**
 * @phpstan-import-type TypeErrors from Client
 */
final class ErrorMessage
{
	/**
	 * @param TypeErrors $messages
	 */
	public static function join(array $messages): string
	{
		$errors = [];
		foreach ($messages as $error) {
			$errors[] = sprintf('%s: %s', $error['level'], $error['message']);
		}

		return implode('; ', $errors);
	}
}
