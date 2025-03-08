<?php

declare(strict_types = 1);

namespace App\Entity;

use App\Entity\Columns\IdColumn;
use App\Helpers\Str as StrAlias;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
#[ORM\UniqueConstraint(
	columns: ['height', 'width', 'length', 'id']
)]
final class Goods
{
	use IdColumn;

	#[ORM\Column(type: Types::FLOAT)]
	private float $height;
	#[ORM\Column(type: Types::FLOAT)]
	private float $width;
	#[ORM\Column(type: Types::FLOAT)]
	private float $length;

	public function __construct(float $height, float $width, float $length)
	{
		$this->height = $height;
		$this->width = $width;
		$this->length = $length;
	}

	public function getHeight(): float
	{
		return $this->height;
	}

	public function getKey(): string
	{
		return StrAlias::key($this->height, $this->width, $this->length);
	}

	public function getWidth(): float
	{
		return $this->width;
	}

	public function getLength(): float
	{
		return $this->length;
	}

}
