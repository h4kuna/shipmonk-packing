<?php

declare(strict_types = 1);

namespace App\Api\TreeDBinPacking\Exceptions;

use App\Api\Exceptions\ResponseException;
use App\Api\TreeDBinPacking\Client;
use App\Api\TreeDBinPacking\Helpers\ErrorMessage;

/**
 * @phpstan-import-type TypeErrors from Client
 */
final class TreeDBinPackingResponseErrorsException extends ResponseException
{
	/**
	 * @param TypeErrors $errors
	 */
	public function __construct(public array $errors, int $code)
	{
		parent::__construct(ErrorMessage::join($this->errors), $code);
	}

}
