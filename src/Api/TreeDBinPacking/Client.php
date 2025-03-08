<?php

declare(strict_types = 1);

namespace App\Api\TreeDBinPacking;

use App\Api\Exceptions\BadResponseException;
use App\Api\Exceptions\InvalidJsonResponseException;
use App\Api\Exceptions\ResponseException;
use App\Api\TreeDBinPacking\Entities\Credentials;
use App\Api\TreeDBinPacking\Factories\UriFactory;
use GuzzleHttp\Psr7\Utils;
use Nette\Utils\Json;
use Nette\Utils\JsonException;
use Psr\Http\Client\ClientExceptionInterface;
use Psr\Http\Client\ClientInterface;
use Psr\Http\Message\RequestFactoryInterface;

/**
 * @phpstan-type TypeErrors array<array{level: string, message: string}>
 * @phpstan-type TypeResponse array{response: array{errors: TypeErrors, status: int, not_packed_items: array<array<string, scalar>>}}
 */
final readonly class Client
{
	public function __construct(
		private ClientInterface $client,
		private RequestFactoryInterface $requestFactory,
		private UriFactory $uriFactory,
		private Credentials $credentials,
		private CheckResponseForErrors $checkResponseForErrors,
	) {}

	/**
	 * @param array<mixed> $body
	 * @return array<mixed>
	 *
	 * @throws ResponseException
	 * @throws ClientExceptionInterface
	 */
	public function request(string $path, array $body): array
	{
		$body['username'] = $this->credentials->username;
		$body['api_key'] = $this->credentials->apiKey;

		$request = $this->requestFactory->createRequest('POST', $this->uriFactory->createUri($path))
			->withBody(Utils::streamFor(Json::encode($body)));

		$response = $this->client->sendRequest($request);

		$code = $response->getStatusCode();
		if (200 <= $code && $code < 300) {
			try {
				/** @var TypeResponse $responseData */
				$responseData = Json::decode((string)$response->getBody(), true);
				$this->checkResponseForErrors->execute($responseData);

				return $responseData['response'];
			} catch (JsonException $e) {
				throw new InvalidJsonResponseException($e);
			}
		}

		throw new BadResponseException($response);
	}
}
