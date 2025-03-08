<?php

declare(strict_types = 1);

namespace App\Api\Exceptions;

use Psr\Http\Message\ResponseInterface;

final class BadResponseException extends ResponseException
{
	public function __construct(public readonly ResponseInterface $response)
	{
		parent::__construct('', $this->response->getStatusCode());
	}
}
