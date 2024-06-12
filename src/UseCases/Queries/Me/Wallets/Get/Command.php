<?php

declare(strict_types=1);

namespace App\UseCases\Queries\Me\Wallets\Get;

use App\Interfaces\Constants\Numeric;
use Symfony\Component\Validator\Constraints as Assert;

final class Command
{
    public function __construct(
        #[Assert\Positive]
        #[Assert\LessThan(Numeric::DB_INT_MAX)]
        public int $page = 1,
        #[Assert\Positive]
        #[Assert\LessThan(300)]
        public int $limit = 50
    ) {
    }
}

