<?php

declare(strict_types=1);

namespace Whalar\Tests\Unit\Core\Application\Query\Character\RetrieveCharacter;

use Whalar\Core\Application\Query\Character\RetrieveCharacter\RetrieveCharacterQuery;
use Whalar\Core\Application\Query\Character\RetrieveCharacter\RetrieveCharacterQueryHandler;
use Whalar\Core\Application\Query\Character\RetrieveCharacter\RetrieveCharacterResponse;
use Whalar\Core\Domain\Character\Exception\CharacterNotFoundException;
use Whalar\Core\Domain\Character\Repository\CharacterRepository;
use Whalar\Core\Domain\Character\Service\CharacterFinder;
use Whalar\Core\Domain\Character\ValueObject\CharacterKingsGuard;
use Whalar\Core\Domain\Character\ValueObject\CharacterRoyal;
use Whalar\Tests\Shared\Core\Domain\Character\Aggregate\CharacterMother;
use Whalar\Tests\Shared\Core\Domain\Character\ValueObject\CharacterIdMother;
use Whalar\Tests\Shared\Core\Infrastructure\Persistence\InMemory\InMemoryCharacterRepository;
use Whalar\Tests\Shared\Shared\Infrastructure\PHPUnit\UnitTestCase;

final class RetrieveCharacterQueryHandlerTest extends UnitTestCase
{
    private CharacterRepository $characters;

    public function testRetrieveCharacterSuccessfully(): void
    {
        $character = CharacterMother::create(
            royal: CharacterRoyal::from(true),
            kingsGuard: CharacterKingsGuard::from(true),
        );
        $this->characters->save($character);

        $response = $this->retrieveCharacter(new RetrieveCharacterQuery($character->internalId()->value()));

        self::assertEquals(
            [
                'characterName' => $character->name(),
                'characterImageThumb' => $character->imageThumb(),
                'characterImageFull' => $character->imageFull(),
                'characterLink' => $character->internalId()->link(),
                'nickname' => $character->nickname(),
                'royal' => $character->royal()->value(),
                'kingsguard' => $character->kingsGuard()->value(),
                'actors' => [
                    $character->actors()[0],
                ],
            ],
            $response->jsonSerialize(),
        );
    }

    public function testTryRetrieveCharacterNotFoundShouldThrowException(): void
    {
        $this->expectException(CharacterNotFoundException::class);
        $this->retrieveCharacter(new RetrieveCharacterQuery(CharacterIdMother::create()));
    }

    protected function setUp(): void
    {
        parent::setUp();

        $this->characters = new InMemoryCharacterRepository();
    }

    private function retrieveCharacter(RetrieveCharacterQuery $query): RetrieveCharacterResponse
    {
        return (new RetrieveCharacterQueryHandler(new CharacterFinder($this->characters)))($query);
    }
}
