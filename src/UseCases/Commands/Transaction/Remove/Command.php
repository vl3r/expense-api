<?php

declare(strict_types=1);

namespace App\UseCases\Commands\Transaction\Remove;

use App\Interfaces\Validators\Constraints\RequiredUuid4;

final readonly class Command
{
    public function __construct(
        #[RequiredUuid4]
        public string $id
    )
    {
    }
}
