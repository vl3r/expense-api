<?php

declare(strict_types=1);

namespace App\Entities\DTO\Transaction;

use DateTimeImmutable;
use Money\Money;

final readonly class TransactionDTO
{
    public function __construct(
        public string $id,
        public string $walletId,
        public Money $amount,
        public DateTimeImmutable $committedAt,
    ) {
    }
}
