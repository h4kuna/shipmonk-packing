<?php

declare(strict_types = 1);

namespace App\Queries;

use App\Api\TreeDBinPacking\Contracts\GetPackagesQueryContract;
use App\Entity\Packaging;
use Doctrine\ORM\EntityManagerInterface;
use Generator;
use Psr\Log\LoggerInterface;

final class GetPackagesQuery implements GetPackagesQueryContract
{
	public function __construct(private EntityManagerInterface $entityManager, private LoggerInterface $logger) {}

	public function execute(): Generator
	{
		/** @var list<Packaging> $packages */
		$packages = $this->entityManager->createQueryBuilder()
			->from(Packaging::class, 'p')
			->select('p')
			->getQuery()
			->execute();

		$i = 0;
		foreach ($packages as $package) {
			if ($i === 500) {
				$this->logger->info('Limit of 500 packages reached');
			}
			yield $package;
			++$i;
		}
	}
}
