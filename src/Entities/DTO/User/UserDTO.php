<?php

declare(strict_types=1);

namespace App\Entities\DTO\User;

final class UserDTO
{
    public function __construct(
        public readonly string $id,
        public readonly string $email,
    ) {
    }
}
