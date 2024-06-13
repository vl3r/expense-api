<?php

declare(strict_types=1);

namespace App\UseCases\Commands\Category\Remove;

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
