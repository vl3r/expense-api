<?php

declare(strict_types=1);

namespace App\Entities\DTO\Wallet;

final readonly class WalletDTO
{
    public function __construct(
        public string $id,
        public string $name,
        public string $userId,
    ) {
    }
}
