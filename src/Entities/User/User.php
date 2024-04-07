<?php

declare(strict_types=1);

namespace App\Entities\User;

use Ramsey\Uuid\Uuid;

class User
{
    private readonly string $id;

    public function __construct(private readonly string $email)
    {
        $this->id = Uuid::uuid4()->toString();
    }

    public function getId(): string
    {
        return $this->id;
    }

    public function getEmail(): string
    {
        return $this->email;
    }
}
