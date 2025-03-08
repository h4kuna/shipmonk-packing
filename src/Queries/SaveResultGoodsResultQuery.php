<?php

declare(strict_types = 1);

namespace App\Queries;

use App\Entity\Goods;
use App\Entity\Groups;
use App\Entity\Packaging;
use App\Entity\PackagingGroupGood;
use App\Goods\ItemsCollection;
use Doctrine\ORM\EntityManagerInterface;

final readonly class SaveResultGoodsResultQuery
{
	public function __construct(
		private EntityManagerInterface $entityManager,
		private CheckGroupDuplicityQuery $checkGroupDuplicityQuery,
		private FindExistingGoodsQuery $findExistingGoodsQuery,
	) {}

	public function execute(Packaging $packaging, ItemsCollection $items): void
	{
		$isDuplicity = $this->checkGroupDuplicityQuery->execute($items, $packaging->getId());

		if ($isDuplicity) {
			return;
		}

		$goods = $this->findExistingGoodsQuery->execute($items);

		$groups = new Groups(count($items));
		$this->entityManager->persist($groups);

		foreach ($items as $item) {
			$good = new Goods($item->height, $item->width, $item->length);

			$key = $good->getKey();
			if (isset($goods[$key])) {
				$good = $goods[$key];
			} else {
				$this->entityManager->persist($good);
			}

			$packagingGroupGood = new PackagingGroupGood($groups, $packaging, $good);
			$this->entityManager->persist($packagingGroupGood);
		}

		$this->entityManager->flush();
	}
}
