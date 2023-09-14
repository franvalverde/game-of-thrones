<?php

declare(strict_types=1);

namespace Whalar\Tests\Shared\Shared\Infrastructure\Behat\Context;

use Behat\Behat\Context\Context;
use Doctrine\Common\DataFixtures\Purger\ORMPurger;
use Doctrine\ORM\EntityManagerInterface;

final class ApplicationContext implements Context
{
    private const EXCLUDED_TABLES = ['language', 'document.typology'];

    private static EntityManagerInterface $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        self::$entityManager = $entityManager;
    }

    /**
     * @BeforeFeature
     * @AfterScenario @PurgeDatabase
     */
    public static function purgeDatabase(): void
    {
        $purger = new ORMPurger(self::$entityManager, self::EXCLUDED_TABLES);
        $purger->purge();
    }

    /** @Then /^the database "([^"]*)" should contain the resource with ID "([^"]*)"$/ */
    public function theDatabaseShouldContainTheResourceWithId(string $table, string $id): void
    {
        $queryBuilder = self::$entityManager->getConnection()->createQueryBuilder();

        $queryBuilder
            ->select('id')
            ->from($table, 't')
            ->where('id = :id')
            ->setParameter('id', $id);

        $result = $queryBuilder->fetchOne();

        if ($result === false) {
            throw new \RuntimeException(
                sprintf('The resource with ID "%s" does not exist in the "%s" table.', $id, $table),
            );
        }
    }
}
