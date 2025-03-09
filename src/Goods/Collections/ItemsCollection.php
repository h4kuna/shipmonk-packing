<?php

declare(strict_types = 1);

namespace App\Goods\Collections;

use App\Goods\Entities\ItemEntity;
use ArrayIterator;
use Countable;
use IteratorAggregate;
use Traversable;

/**
 * @implements IteratorAggregate<int, ItemEntity>
 */
final readonly class ItemsCollection implements IteratorAggregate, Countable
{
	/**
	 * @param list<ItemEntity> $items
	 */
	public function __construct(private array $items) {}

	public function getIterator(): Traversable
	{
		return new ArrayIterator($this->items);
	}

	public function count(): int
	{
		return count($this->items);
	}

}
