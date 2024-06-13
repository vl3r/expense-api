<?php

declare(strict_types=1);

namespace App\Entities\DTO\Category;

final readonly class CategoryDTO
{
    public function __construct(
        public string $id,
        public string $name,
    ) {
    }
}
