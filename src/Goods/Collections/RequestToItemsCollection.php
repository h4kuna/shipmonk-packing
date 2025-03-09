<?php

declare(strict_types = 1);

namespace App\Goods\Collections;

use App\Goods\Actions\SortItemFieldsAction;
use App\Goods\Actions\SortItemsAction;
use App\Goods\Entities\ItemEntity;
use Nette\Schema\Expect;
use Nette\Schema\Processor;
use Nette\Schema\ValidationException;
use Nette\Utils\Json;
use Nette\Utils\JsonException;
use Psr\Http\Message\RequestInterface;

final readonly class RequestToItemsCollection
{
	public function __construct(
		private Processor $processor,
		private SortItemFieldsAction $sortItemFieldsAction,
		private SortItemsAction $sortItemsAction,
	) {}

	/**
	 * @throws JsonException
	 * @throws ValidationException
	 */
	public function transform(RequestInterface $request): ItemsCollection
	{
		$goods = Json::decode((string)$request->getBody(), true);

		$package = Expect::structure([
			'length' => Expect::type('float|int')->castTo('float')->required(),
			'height' => Expect::type('float|int')->castTo('float')->required(),
			'width' => Expect::type('float|int')->castTo('float')->required(),
			'weight' => Expect::type('float|int')->castTo('float')->required(),
		])->castTo(ItemEntity::class);

		$schema = Expect::listOf($package)->min(1);

		/** @var list<ItemEntity> $collection */
		$collection = $this->processor->process($schema, $goods);

		$items = [];
		foreach ($collection as $item) {
			$items[] = $this->sortItemFieldsAction->execute($item);
		}

		return new ItemsCollection($this->sortItemsAction->execute($items));
	}

}
