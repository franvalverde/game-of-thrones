<?php

declare(strict_types=1);

namespace Whalar\Tests\Unit\Core\Application\Command\Character\RelateCharacters;

use Assert\AssertionFailedException;
use Whalar\Core\Application\Command\Character\RelateCharacters\RelateCharactersCommand;
use Whalar\Core\Application\Command\Character\RelateCharacters\RelateCharactersCommandHandler;
use Whalar\Core\Domain\Character\Aggregate\Character;
use Whalar\Core\Domain\Character\Exception\CharacterNotFoundException;
use Whalar\Core\Domain\Character\Repository\CharacterRepository;
use Whalar\Core\Domain\Character\Service\CharacterFinder;
use Whalar\Core\Domain\CharacterRelate\Event\CharacterWasRelated;
use Whalar\Core\Domain\CharacterRelate\Exception\CharacterRelateInvalidExistsException;
use Whalar\Core\Domain\CharacterRelate\Exception\InvalidCharacterRelationTypeException;
use Whalar\Core\Domain\CharacterRelate\Repository\CharacterRelateRepository;
use Whalar\Core\Domain\CharacterRelate\Service\CharacterLinker;
use Whalar\Shared\Infrastructure\Messaging\DomainEventPublisher;
use Whalar\Tests\Shared\Core\Application\Command\Character\RelateCharacters\RelateCharactersCommandMother;
use Whalar\Tests\Shared\Core\Domain\Character\Aggregate\CharacterMother;
use Whalar\Tests\Shared\Core\Domain\CharacterRelate\ValueObject\CharacterRelationMother;
use Whalar\Tests\Shared\Core\Infrastructure\Persistence\InMemory\InMemoryCharacterRelateRepository;
use Whalar\Tests\Shared\Core\Infrastructure\Persistence\InMemory\InMemoryCharacterRepository;
use Whalar\Tests\Shared\Shared\Infrastructure\PHPUnit\UnitTestCase;

final class RelateCharactersCommandHandlerTest extends UnitTestCase
{
    private CharacterRepository $characters;
    private CharacterRelateRepository $relates;
    private Character $principalCharacter;
    private Character $relatedTo;

    /** @throws AssertionFailedException */
    public function testCharacterIsRelatedSuccessfully(): void
    {
        $relation = CharacterRelationMother::random();

        $relateCommand = RelateCharactersCommandMother::create(
            characterId: $this->principalCharacter->internalId()->value(),
            relatedTo: $this->relatedTo->internalId()->value(),
            relation: $relation->value(),
        );

        $this->relateCharacters($relateCommand);

        self::assertInstanceOf(InMemoryCharacterRelateRepository::class, $this->relates);
        $relateCreated = $this->relates->ofPrincipalId($this->principalCharacter->internalId());

        self::assertNotNull($relateCreated);
        self::assertEquals($relateCreated->id()->id(), $relateCommand->relationId);
        self::assertTrue($relateCreated->character()?->id()->equalsTo($this->principalCharacter->id()));
        self::assertTrue($relateCreated->relatedTo()?->id()->equalsTo($this->relatedTo->id()));
        self::assertTrue($relateCreated->relation()->equalsTo($relation));

        $events = DomainEventPublisher::instance()->events();

        self::assertCount(1, $events);
        self::assertInstanceOf(CharacterWasRelated::class, $events[0]);
        self::assertEquals($events[0]->messageAggregateId(), $relateCreated->id()->id());
        self::assertEquals('character_relate', $events[0]->messageAggregateName());
        self::assertEquals($events[0]->relatedTo(), $this->relatedTo->internalId()->value());
        self::assertEquals($events[0]->relation(), $relateCommand->relationType);
        self::assertEquals($events[0]->characterId(), $this->principalCharacter->internalId()->value());
    }

    /** @throws AssertionFailedException */
    public function testTryRelateCharactersWithRelatedToNotFoundThrowException(): void
    {
        $this->expectException(CharacterNotFoundException::class);
        $this->relateCharacters(
            RelateCharactersCommandMother::create(
                characterId: $this->principalCharacter->internalId()->value(),
            ),
        );
    }

    /** @throws AssertionFailedException */
    public function testTryRelateCharactersWithInvalidRelationTypeThrowException(): void
    {
        $this->expectException(InvalidCharacterRelationTypeException::class);
        $this->relateCharacters(
            RelateCharactersCommandMother::create(
                characterId: $this->principalCharacter->internalId()->value(),
                relatedTo: $this->relatedTo->internalId()->value(),
                relation: 'invalid',
            ),
        );
    }

    /** @throws AssertionFailedException */
    public function testTryRelateCharactersWithCharacterNotFoundThrowException(): void
    {
        $this->expectException(CharacterNotFoundException::class);
        $this->relateCharacters(RelateCharactersCommandMother::create());
    }

    public function testTryRelateCharacterToTheSameCharacterShouldThrowException(): void
    {
        $relateCommand = RelateCharactersCommandMother::create(
            characterId: $this->principalCharacter->internalId()->value(),
            relatedTo: $this->principalCharacter->internalId()->value(),
        );

        $this->expectException(CharacterRelateInvalidExistsException::class);
        $this->relateCharacters($relateCommand);
    }

    protected function setUp(): void
    {
        parent::setUp();

        $this->characters = new InMemoryCharacterRepository();
        $this->relates = new InMemoryCharacterRelateRepository();

        $this->principalCharacter = CharacterMother::create();
        $this->relatedTo = CharacterMother::create();

        $this->characters->save($this->principalCharacter);
        $this->characters->save($this->relatedTo);
    }

    private function relateCharacters(RelateCharactersCommand $command): void
    {
        (new RelateCharactersCommandHandler(
            new CharacterFinder($this->characters),
            new CharacterLinker($this->relates),
        ))($command);
    }
}
