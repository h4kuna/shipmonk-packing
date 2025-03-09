<?php

declare(strict_types = 1);

namespace App;

use App\Api\Exceptions\ResponseException;
use App\Api\TreeDBinPacking\Endpoints\PackAShipmentEndpoint;
use App\Api\TreeDBinPacking\Exceptions\CouldNotPackToOnePackageException;
use App\Contracts\FindOptimalPackageQueryContract;
use App\Goods\Actions\FindMaxPackageWeightAction;
use App\Goods\Collections\RequestToItemsCollection;
use App\Goods\Factories\MetaDataItemFactory;
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
		private FindOptimalPackageQueryContract $findOptimalPackageQuery,
		private ResponseFactory $responseFactory,
		private PackAShipmentEndpoint $packAShipmentEndpoint,
		private SaveResultGoodsResultQuery $saveResultGoodsResultQuery,
		private LoggerInterface $logger,
		private MetaDataItemFactory $metaDataItemFactory,
		private FindMaxPackageWeightAction $findMaxPackageWeightAction,
	) {}

	public function run(RequestInterface $request): ResponseInterface
	{
		try {
			$items = $this->requestToItemsCollection->transform($request);
		} catch (JsonException) {
			return $this->responseFactory->createErrorResponse('Invalid JSON format');
		} catch (ValidationException $e) {
			return $this->responseFactory->createErrorResponse('Validation failed: ' . $e->getMessage());
		}

		$metaDataItems = $this->metaDataItemFactory->create($items);

		if ($metaDataItems->weight > $this->findMaxPackageWeightAction->execute()) {
			return $this->responseFactory->createErrorResponse(
				'The weight of the packages is too heavy, we don\'t have the boxes for it.',
			);
		}

		try {
			$packageFromApi = $this->packAShipmentEndpoint->request($items);

			$this->saveResultGoodsResultQuery->execute($packageFromApi, $items);

			return $this->responseFactory->createSuccess($packageFromApi);
		} catch (CouldNotPackToOnePackageException) {
			return $this->responseFactory->createErrorResponse('Cannot be packed into one package.');
		} catch (ClientExceptionInterface|ResponseException $e) {
			$this->logger->error($e->getMessage(), ['exception' => $e]);
		} catch (Throwable $e) {
			$this->logger->error($e->getMessage(), ['exception' => $e]);
			// todo return message "server is down" or return any package?
		}

		$package = $this->findOptimalPackageQuery->execute($metaDataItems);
		if ($package === null) {
			return $this->responseFactory->createErrorResponse(
				'Is too heavy or package must be bigger than we have.',
				404,
			);
		}

		return $this->responseFactory->createSuccess($package);
	}
}
