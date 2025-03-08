<?php

declare(strict_types = 1);

namespace App\Http;

use App\Entity\Packaging;
use GuzzleHttp\Psr7\Response;
use Nette\Utils\Json;
use Psr\Http\Message\ResponseInterface;

final class ResponseFactory
{
	public function createSuccess(Packaging $packaging): ResponseInterface
	{
		return new Response(
			200,
			[
				'Content-Type' => 'application/json',
			],
			Json::encode(
				[
					'width' => $packaging->getWidth(),
					'height' => $packaging->getHeight(),
					'length' => $packaging->getLength(),
					'maxWeight' => $packaging->getMaxWeight(),
				],
			),
		);
	}

	public function createErrorResponse(string $message, int $statusCode = 400): ResponseInterface
	{
		return new Response(
			$statusCode,
			[
				'Content-Type' => 'application/json',
			],
			Json::encode(['error' => $message]),
		);
	}
}
