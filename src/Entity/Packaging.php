<?php

namespace App\Entity;

use App\Entity\Columns\IdColumn;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

/**
 * Represents a box available in the warehouse.
 *
 * Warehouse workers pack a set of products for a given order into one of these boxes.
 */
#[ORM\Entity]
#[ORM\UniqueConstraint(
	columns: ['maxWeight', 'height', 'width', 'length']
)]
class Packaging
{
	use IdColumn;

	#[ORM\Column(type: Types::FLOAT)]
	private float $width;
	#[ORM\Column(type: Types::FLOAT)]
	private float $height;
	#[ORM\Column(type: Types::FLOAT)]
	private float $length;
	#[ORM\Column(type: Types::FLOAT)]
	private float $maxWeight;

	public function __construct(float $width, float $height, float $length, float $maxWeight)
	{
		$this->width = $width;
		$this->height = $height;
		$this->length = $length;
		$this->maxWeight = $maxWeight;
	}

	public function getWidth(): float
	{
		return $this->width;
	}

	public function getHeight(): float
	{
		return $this->height;
	}

	public function getLength(): float
	{
		return $this->length;
	}

	public function getMaxWeight(): float
	{
		return $this->maxWeight;
	}

}
