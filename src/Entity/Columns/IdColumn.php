<?php

declare(strict_types = 1);

namespace App\Entity\Columns;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\DBAL\Types\Types;

trait IdColumn
{
	#[ORM\Id]
	#[ORM\Column(type: Types::INTEGER, options: ['unsigned' => true])]
	#[ORM\GeneratedValue]
	private ?int $id = null;

	public function getId(): int
	{
		assert($this->id !== null);

		return $this->id;
	}
}
