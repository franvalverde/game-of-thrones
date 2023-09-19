<?php

declare(strict_types=1);

namespace Whalar\Core\Domain\CharacterRelate\ValueObject;

use Whalar\Core\Domain\Character\Exception\InvalidCharacterRelationTypeException;

enum CharacterRelation
{
    case AbductedBy;
    case GuardianOf;
    case Killed;
    case KilledBy;
    case MarriedEngaged;
    case ParentOf;
    case Parents;
    case Siblings;

    public static function from(string $value): self
    {
        return match ($value) {
            'abductedBy' => self::AbductedBy,
            'guardianOf' => self::GuardianOf,
            'killed' => self::Killed,
            'killedBy' => self::KilledBy,
            'marriedEngaged' => self::MarriedEngaged,
            'parentOf' => self::ParentOf,
            'parents' => self::Parents,
            'siblings' => self::Siblings,
            default => throw InvalidCharacterRelationTypeException::from($value),
        };
    }

    public function value(): string
    {
        return match ($this) {
            self::AbductedBy => 'abductedBy',
            self::GuardianOf => 'guardianOf',
            self::Killed => 'killed',
            self::KilledBy => 'killedBy',
            self::MarriedEngaged => 'marriedEngaged',
            self::ParentOf => 'parentOf',
            self::Parents => 'parents',
            self::Siblings => 'siblings',
        };
    }
}
