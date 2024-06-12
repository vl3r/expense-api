<?php

declare(strict_types=1);

namespace App\Entities\Transaction;

use App\Entities\DTO\Transaction\TransactionDTO;
use DateTimeImmutable;
use Money\Money;
use Ramsey\Uuid\Uuid;

class Transaction
{
    private readonly string $id;

    public function __construct(
        private readonly string $walletId,
        private Money $amount,
        private DateTimeImmutable $committedAt,
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

    public function getCommittedAt(): DateTimeImmutable
    {
        return $this->committedAt;
    }

    public function changeCommittedAt(DateTimeImmutable $committedAt): void
    {
        $this->committedAt = $committedAt;
    }

    public function toDTO(): TransactionDTO
    {
        return new TransactionDTO(
            id: $this->id,
            walletId: $this->walletId,
            amount: $this->amount,
            committedAt: $this->committedAt,
        );
    }
}
