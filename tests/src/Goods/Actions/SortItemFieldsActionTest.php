<?php

declare(strict_types = 1);

namespace Tests\Goods\Actions;

use App\Goods\Actions\SortItemFieldsAction;
use App\Goods\ItemEntity;
use Closure;
use PHPUnit\Framework\Assert;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

final class SortItemFieldsActionTest extends TestCase
{
	/**
	 * @return array<string|int, array{0: Closure(static):void}>
	 */
	public static function data(): array
	{
		return [
			[
				static function (self $self) {
					$self->assert(
						new ItemEntity(10, 10, 10, 5.5),
						10,
						10,
						10,
					);
				},
			],
			[
				static function (self $self) {
					$self->assert(
						new ItemEntity(10, 10, 9, 5.5),
						9,
						10,
						10,
					);
				},
			],
			[
				static function (self $self) {
					$self->assert(
						new ItemEntity(10, 10, 9, 5.5),
						10,
						9,
						10,
					);
				},

			],
			[
				static function (self $self) {
					$self->assert(
						new ItemEntity(10, 10, 9, 5.5),
						10,
						10,
						9,
					);
				},

			],
			[
				static function (self $self) {
					$self->assert(
						new ItemEntity(10, 9, 9, 5.5),
						9,
						9,
						10,
					);
				},

			],
			[
				static function (self $self) {
					$self->assert(
						new ItemEntity(10, 9, 9, 5.5),
						9,
						10,
						9,
					);
				},

			],
			[
				static function (self $self) {
					$self->assert(
						new ItemEntity(10, 9, 9, 5.5),
						10,
						9,
						9,
					);
				},
			],
			[
				static function (self $self) {
					$self->assert(
						new ItemEntity(3, 2, 1, 5.5),
						1,
						2,
						3,
					);
				},
			],
			[
				static function (self $self) {
					$self->assert(
						new ItemEntity(3, 2, 1, 5.5),
						3,
						2,
						1,
					);
				},
			],
			[
				static function (self $self) {
					$self->assert(
						new ItemEntity(6.4, 5.4, 4.8, 5.5),
						5.4,
						6.4,
						4.8,
					);
				},
			],
		];
	}

	/**
	 * @param Closure(static):void $assert
	 */
	#[DataProvider('data')]
	public function test(Closure $assert): void
	{
		$assert($this);
	}

	public function assert(
		ItemEntity $expected,
		float $x,
		float $y,
		float $z,
	): void {
		$item = new ItemEntity($x, $y, $z, $expected->weight);
		$actual = new SortItemFieldsAction()->execute($item);

		Assert::assertSame($expected->length, $actual->length);
		Assert::assertSame($expected->width, $actual->width);
		Assert::assertSame($expected->height, $actual->height);
		Assert::assertSame($expected->weight, $actual->weight);
	}
}
