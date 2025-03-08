<?php

declare(strict_types = 1);

namespace App\Api\Exceptions;

use Throwable;

final class InvalidJsonResponseException extends ResponseException
{
	public function __construct(Throwable $previous)
	{
		parent::__construct('Invalid json', 500, $previous);
	}
}
