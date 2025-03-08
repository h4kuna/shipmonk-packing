<?php

declare(strict_types = 1);

namespace App\Helpers;

final class Str
{
	/**
	 * @param scalar|null ...$data
	 */
	public static function key(...$data): string
	{
		return implode("\x00", $data);
	}
}
