<?php

declare(strict_types=1);

namespace Whalar\Tests\Shared\Core\Infrastructure\Behat\Context;

use Behat\Behat\Context\Context;
use Behat\Gherkin\Node\TableNode;
use Whalar\Core\Domain\Character\Repository\CharacterRepository;
use Whalar\Core\Domain\Character\ValueObject\CharacterId;
use Whalar\Shared\Domain\ValueObject\AggregateId;
use Whalar\Shared\Domain\ValueObject\Name;
use Whalar\Tests\Shared\Core\Domain\Character\Aggregate\CharacterMother;

final class CharacterContext implements Context
{
    public function __construct(private readonly CharacterRepository $characters)
    {
    }

    /** @Given /^the following character exist:$/ */
    public function theFollowingCharacterExist(TableNode $table): void
    {
        foreach ($table->getHash() as $row) {
            $character = CharacterMother::create(
                id: AggregateId::from($row['id']),
                internalId: CharacterId::from($row['internalId']),
                name: Name::from($row['name']),
            );

            $this->characters->save($character);
        }
    }
}
