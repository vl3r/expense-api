<?php

declare(strict_types=1);

namespace App\Entities\DTO\WalletStream;

class WalletStreamDTO
{
    public function __construct(public string $id, public string $title, public bool $isDisabled, public int $sort)
    {
    }
}
