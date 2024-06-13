<?php

declare(strict_types=1);

namespace App\Entities\Category;

use App\Entities\DTO\Category\CategoryDTO;
use Ramsey\Uuid\Uuid;

class Category
{
    private readonly string $id;

    public function __construct(private string $name)
    {
        $this->id = Uuid::uuid4()->toString();
    }

    public function getId(): string
    {
        return $this->id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function changeName(string $name): void
    {
        $this->name = $name;
    }

    public function toDTO(): CategoryDTO
    {
        return new CategoryDTO(
            id: $this->id,
            name: $this->name,
        );
    }
}
