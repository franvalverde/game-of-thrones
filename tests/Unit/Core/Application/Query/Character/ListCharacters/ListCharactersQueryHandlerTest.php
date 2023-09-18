<?php

declare(strict_types=1);

namespace Whalar\Tests\Unit\Core\Application\Query\Character\ListCharacters;

use Whalar\Core\Application\Query\Character\ListCharacters\ListCharactersQuery;
use Whalar\Core\Application\Query\Character\ListCharacters\ListCharactersQueryHandler;
use Whalar\Core\Application\Query\Character\ListCharacters\ListCharactersResponse;
use Whalar\Core\Domain\Character\Repository\CharacterRepository;
use Whalar\Core\Domain\Character\ValueObject\CharacterKingsGuard;
use Whalar\Core\Domain\Character\ValueObject\CharacterRoyal;
use Whalar\Shared\Domain\ValueObject\Name;
use Whalar\Tests\Shared\Core\Domain\Character\Aggregate\CharacterMother;
use Whalar\Tests\Shared\Core\Infrastructure\Persistence\InMemory\InMemoryCharacterRepository;
use Whalar\Tests\Shared\Shared\Infrastructure\PHPUnit\UnitTestCase;

final class ListCharactersQueryHandlerTest extends UnitTestCase
{
    private CharacterRepository $characters;

    public function testListCharactersSuccessfully(): void
    {
        $page = 1;
        $size = 100;
        $order = 'desc';

        $character = CharacterMother::create(
            royal: CharacterRoyal::from(true),
            kingsGuard: CharacterKingsGuard::from(true),
        );
        $this->characters->save($character);

        $response = $this->listCharacters(new ListCharactersQuery($page, $size, $order, null));

        self::assertEquals(
            [
                [
                    'characterName' => $character->name(),
                    'characterImageThumb' => $character->imageThumb(),
                    'characterImageFull' => $character->imageFull(),
                    'characterLink' => $character->internalId()->link(),
                    'nickname' => $character->nickname(),
                    'royal' => $character->royal()->value(),
                    'kingsguard' => $character->kingsGuard()->value(),
                    'actorName' => $character->actors()[0]?->name()->value(),
                    'actorLink' => $character->actors()[0]?->internalId()->link(),
                ],
            ],
            $response->jsonSerialize()['characters'],
        );

        self::assertEquals(
            [
                'currentPage' => $page,
                'lastPage' => 1,
                'size' => $size,
                'total' => 1,
            ],
            $response->jsonSerialize()['meta'],
        );
    }

    public function testListCharactersFilteringByNameSuccessfully(): void
    {
        $discartCharacter = CharacterMother::create(name: Name::from('King Joffrey Baratheon Dwarf'));
        $this->characters->save($discartCharacter);
        $wishCharacter = CharacterMother::create(name: Name::from('Khal Rhalko'));
        $this->characters->save($wishCharacter);

        $page = 1;
        $size = 2;
        $filterByName = 'Khal';

        $response = $this->listCharacters(new ListCharactersQuery($page, $size, 'desc', $filterByName));

        self::assertEquals($wishCharacter->name(), $response->jsonSerialize()['characters'][0]['characterName']);

        self::assertEquals(
            [
                'currentPage' => $page,
                'lastPage' => 1,
                'size' => $size,
                'total' => 1,
            ],
            $response->jsonSerialize()['meta'],
        );
    }

    protected function setUp(): void
    {
        parent::setUp();

        $this->characters = new InMemoryCharacterRepository();
    }

    private function listCharacters(ListCharactersQuery $query): ListCharactersResponse
    {
        return (new ListCharactersQueryHandler($this->characters))($query);
    }
}
