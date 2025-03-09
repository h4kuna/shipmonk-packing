<?php

declare(strict_types = 1);

namespace App\Queries;

use App\Contracts\FindOptimalPackageQueryContract;
use App\Entity\Packaging;
use App\Goods\Entities\MetaDataItemEntity;
use Doctrine\ORM\EntityManagerInterface;

final readonly class FindOptimalPackageQuery implements FindOptimalPackageQueryContract
{
	public function __construct(private EntityManagerInterface $entityManager) {}

	public function execute(MetaDataItemEntity $metaDataItem): ?Packaging
	{
		/** @var list<Packaging> $packaging */
		$packaging = $this->entityManager->createQueryBuilder()
			->select('p')
			->from(Packaging::class, 'p')
			->where('p.length >= :length')
			->andWhere('p.width >= :width')
			->andWhere('p.height >= :height')
			->andWhere('p.maxWeight >= :maxWeight')
			->andWhere('p.length * p.width * p.height >= :volume') // @todo create column in db
			->orderBy('p.length * p.width * p.height')
			->setParameter('length', $metaDataItem->length)
			->setParameter('width', $metaDataItem->width)
			->setParameter('height', $metaDataItem->height)
			->setParameter('maxWeight', $metaDataItem->weight)
			->setParameter('volume', $metaDataItem->volume)
			->setMaxResults(1)
			->getQuery()
			->execute();

		return $packaging[0] ?? null;
	}
}
