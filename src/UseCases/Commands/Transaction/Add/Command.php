<?php

declare(strict_types=1);

namespace App\UseCases\Commands\Transaction\Add;

use App\Entities\Services\Converter\DTO\PriceDTO;
use App\Interfaces\Validators\Constraints\RequiredUuid4;
use DateTimeImmutable;

final readonly class Command
{
    public function __construct(
        #[RequiredUuid4]
        public string $walletId,
        #[RequiredUuid4]
        public string $categoryId,
        public DateTimeImmutable $commitedAt,
        public PriceDTO $amount,
        public string $note
    ) {
    }
}
