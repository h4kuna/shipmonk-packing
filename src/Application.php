<?php

declare(strict_types = 1);

namespace App;

use App\Actions\HavePackageForGoodsAction;
use App\Api\Exceptions\ResponseException;
use App\Api\TreeDBinPacking\Endpoints\PackAShipmentEndpoint;
use App\Api\TreeDBinPacking\Exceptions\CouldNotPackToOnePackageException;
use App\Api\TreeDBinPacking\Exceptions\TooManyPackagesException;
use App\Goods\RequestToItemsCollection;
use App\Http\ResponseFactory;
use App\Queries\SaveResultGoodsResultQuery;
use Nette\Schema\ValidationException;
use Nette\Utils\JsonException;
use Psr\Http\Client\ClientExceptionInterface;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Log\LoggerInterface;
use Throwable;

final readonly class Application
{
	public function __construct(
		private RequestToItemsCollection $requestToItemsCollection,
		private HavePackageForGoodsAction $havePackageForGoodsAction,
		private ResponseFactory $responseFactory,
		private PackAShipmentEndpoint $packAShipmentEndpoint,
		private SaveResultGoodsResultQuery $saveResultGoodsResultQuery,
		private LoggerInterface $logger,
	) {}

	public function run(RequestInterface $request): ResponseInterface
	{
		try {
			$data = $this->requestToItemsCollection->transform($request);
		} catch (JsonException) {
			return $this->responseFactory->createErrorResponse('Invalid JSON format');
		} catch (ValidationException $e) {
			return $this->responseFactory->createErrorResponse('Validation failed: ' . $e->getMessage());
		}

		try {
			$packageFromApi = $this->packAShipmentEndpoint->request($data);

			$this->saveResultGoodsResultQuery->execute($packageFromApi, $data);

			return $this->responseFactory->createSuccess($packageFromApi);
		} catch (CouldNotPackToOnePackageException $e) {
			return $this->responseFactory->createErrorResponse($e->getMessage());
		} catch (TooManyPackagesException) {
			return $this->responseFactory->createErrorResponse(
				'The goods do not fit into one package.',
			);
		} catch (ClientExceptionInterface|ResponseException $e) {
			$this->logger->error($e->getMessage(), ['exception' => $e]);
		} catch (Throwable $e) {
			$this->logger->error($e->getMessage(), ['exception' => $e]);
			// todo return message "server is down" or return any package?
		}

		$package = $this->havePackageForGoodsAction->execute($data);
		if ($package === null) {
			return $this->responseFactory->createErrorResponse(
				'Is too heavy or package must be bigger than we have.',
				404,
			);
		}

		return $this->responseFactory->createSuccess($package);
	}
}
