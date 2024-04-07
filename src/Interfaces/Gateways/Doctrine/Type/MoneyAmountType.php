<?php

declare(strict_types=1);

namespace App\Interfaces\Gateways\Doctrine\Type;

use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\Type;

final class MoneyAmountType extends Type
{
    public const NAME = 'money_amount';

    /**
     * @param array<array-key, mixed> $column
     */
    public function getSQLDeclaration(array $column, AbstractPlatform $platform): string
    {
        return $platform->getBigIntTypeDeclarationSQL($column);
    }

    //@phpcs:ignore
    public function convertToPHPValue(mixed $value, AbstractPlatform $platform): ?string
    {
        if ($value === null) {
            return null;
        }

        return (string) $value;
    }

    public function convertToDatabaseValue(mixed $value, AbstractPlatform $platform): ?int
    {
        if ($value === null) {
            return null;
        }

        return (int) $value;
    }

    public function getName(): string
    {
        return self::NAME;
    }

    //@phpcs:ignore
    public function requiresSQLCommentHint(AbstractPlatform $platform): bool
    {
        return true;
    }
}
