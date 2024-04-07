<?php

declare(strict_types=1);

namespace App\Entities\Wallet;

use Ramsey\Uuid\Uuid;

class Wallet
{
    private readonly string $id;

    public function __construct(
        private readonly string $userId,
        private string $name,
    ) {
        $this->id = Uuid::uuid4()->toString();
    }

    public function getId(): string
    {
        return $this->id;
    }

    public function getUserId(): string
    {
        return $this->userId;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function changeName(string $name): void
    {
        $this->name = $name;
    }
}
