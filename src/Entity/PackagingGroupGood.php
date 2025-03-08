<?php

declare(strict_types = 1);

namespace App\Entity;

use App\Entity\Columns\IdColumn;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
final class PackagingGroupGood
{
	use IdColumn;

	#[ORM\ManyToOne(targetEntity: Groups::class)]
	#[ORM\JoinColumn(nullable: false)]
	private Groups $group;

	#[ORM\ManyToOne(targetEntity: Packaging::class)]
	#[ORM\JoinColumn(nullable: false)]
	private Packaging $packaging;

	#[ORM\ManyToOne(targetEntity: Goods::class)]
	#[ORM\JoinColumn(nullable: false)]
	private Goods $goods;

	public function __construct(Groups $group, Packaging $packaging, Goods $goods)
	{
		$this->group = $group;
		$this->packaging = $packaging;
		$this->goods = $goods;
	}
}
