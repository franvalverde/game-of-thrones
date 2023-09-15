<?php

declare(strict_types=1);

namespace Whalar\Tests\Shared\Core\Infrastructure\Behat\Context;

use Behat\Behat\Context\Context;
use Behat\Gherkin\Node\TableNode;
use Whalar\Core\Domain\Actor\Repository\ActorRepository;
use Whalar\Core\Domain\Actor\ValueObject\ActorId;
use Whalar\Core\Domain\Actor\ValueObject\SeasonsActive;
use Whalar\Shared\Domain\ValueObject\AggregateId;
use Whalar\Shared\Domain\ValueObject\Name;
use Whalar\Tests\Shared\Core\Domain\Actor\Aggregate\ActorMother;

final class ActorContext implements Context
{
    public function __construct(private readonly ActorRepository $actors)
    {
    }

    /** @Given /^the following actor exist:$/ */
    public function theFollowingActorExist(TableNode $table): void
    {
        foreach ($table->getHash() as $row) {
            $actor = ActorMother::create(
                id: AggregateId::from($row['id']),
                internalId: ActorId::from($row['internalId']),
                name: Name::from($row['name']),
                seasonsActive: array_key_exists('seasonsActive', $row)
                    ? SeasonsActive::fromArray($row['seasonsActive'])
                    : null,
            );

            $this->actors->save($actor);
        }
    }
}
