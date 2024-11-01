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

    private string $note;

    public function __construct(
        private readonly string $walletId,
        private string $categoryId,
        private Money $amount,
        private DateTimeImmutable $committedAt,
        string $note = '',
    ) {
        $this->id   = Uuid::uuid4()->toString();
        $this->note = $note;
    }

    public function getId(): string
    {
        return $this->id;
    }

    public function getWalletId(): string
    {
        return $this->walletId;
    }

    public function getCategoryId(): string
    {
        return $this->categoryId;
    }

    public function changeCategoryId(string $categoryId): void
    {
        $this->categoryId = $categoryId;
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

    public function getNote(): string
    {
        return $this->note;
    }

    public function changeNote(string $note): void
    {
        $this->note = $note;
    }

    public function toDTO(): TransactionDTO
    {
        return new TransactionDTO(
            id: $this->id,
            walletId: $this->walletId,
            categoryId: $this->categoryId,
            amount: $this->amount,
            committedAt: $this->committedAt,
            note: $this->note
        );
    }
}
