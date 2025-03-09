<?php

declare(strict_types = 1);

namespace App\Queries;

use App\Api\Exceptions\InvalidStateException;
use App\Entity\Packaging;
use Doctrine\ORM\EntityManagerInterface;

final readonly class FindMaxPackageWeightQuery
{
	public function __construct(private EntityManagerInterface $entityManager) {}

	public function execute(): float
	{
		/** @var list<Packaging> $packaging */
		$packaging = $this->entityManager->createQueryBuilder()
			->select('p')
			->from(Packaging::class, 'p')
			->orderBy('p.maxWeight', 'DESC')
			->setMaxResults(1)
			->getQuery()
			->execute();

		if (isset($packaging[0]) === false) {
			throw new InvalidStateException('Missing packages. The service does not working.');
		}

		return $packaging[0]->getMaxWeight();
	}
}
