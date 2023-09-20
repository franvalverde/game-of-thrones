<?php

declare(strict_types=1);

namespace Whalar\Core\Infrastructure\Persistence\Doctrine\Repository;

use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;
use Doctrine\Persistence\ObjectRepository;
use Whalar\Core\Domain\CharacterRelate\Aggregate\CharacterRelate;
use Whalar\Core\Domain\CharacterRelate\Repository\CharacterRelateRepository;

final class DoctrineCharacterRelateRepository implements CharacterRelateRepository
{
    private EntityManagerInterface $entityManager;

    /** @var EntityRepository<CharacterRelate>|ObjectRepository<CharacterRelate> */
    // @phpstan-ignore-next-line
    private ObjectRepository|EntityRepository $relates;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
        $this->relates = $entityManager->getRepository(CharacterRelate::class);
    }

    public function save(CharacterRelate $characterRelate): void
    {
        $this->entityManager->persist($characterRelate);
        $this->entityManager->flush();
    }
}
