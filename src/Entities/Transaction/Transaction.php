<?php

declare(strict_types=1);

namespace App\Entities\Transaction;

use DateTimeInterface;
use Money\Money;
use Ramsey\Uuid\Uuid;

class Transaction
{
    private readonly string $id;

    public function __construct(
        private readonly string $walletId,
        private Money $amount,
        private DateTimeInterface $committedAt,
    ) {
        $this->id = Uuid::uuid4()->toString();
    }

    public function getId(): string
    {
        return $this->id;
    }

    public function getWalletId(): string
    {
        return $this->walletId;
    }

    public function getAmount(): Money
    {
        return $this->amount;
    }

    public function changeAmount(Money $amount): void
    {
        $this->amount = $amount;
    }

    public function getCommittedAt(): DateTimeInterface
    {
        return $this->committedAt;
    }

    public function changeCommittedAt(DateTimeInterface $committedAt): void
    {
        $this->committedAt = $committedAt;
    }
}
