<?php

declare(strict_types = 1);

namespace App\Entity;

use App\Entity\Columns\IdColumn;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
final class Groups
{
	use IdColumn;

	#[ORM\Column(type: Types::INTEGER, options: ['unsigned' => true])]
	private int $count;

	public function __construct(int $count)
	{
		$this->count = $count;
	}

}
