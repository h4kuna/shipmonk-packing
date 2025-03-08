<?php

declare(strict_types = 1);

namespace App\Queries;

use App\Contracts\FindPackagingQueryContract;
use App\Entity\Packaging;
use App\Exceptions\PackagingDoesNotFoundException;
use Doctrine\ORM\EntityManagerInterface;

final class FindPackagingQuery implements FindPackagingQueryContract
{
	public function __construct(private EntityManagerInterface $entityManager) {}

	public function execute(int $packagingId): Packaging
	{
		return $this->entityManager->find(Packaging::class, $packagingId) ?? throw new PackagingDoesNotFoundException(
			(string)$packagingId,
		);
	}
}
