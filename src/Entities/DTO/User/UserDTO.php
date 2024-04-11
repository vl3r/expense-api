<?php

declare(strict_types=1);

namespace App\Entities\DTO\User;

final readonly class UserDTO
{
    public function __construct(
        public string $id,
        public string $email,
    ) {
    }
}
