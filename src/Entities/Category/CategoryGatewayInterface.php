<?php

declare(strict_types=1);

namespace App\Entities\Category;


use App\Entities\Transaction\Transaction;

interface CategoryGatewayInterface
{
    public function add(Category $category): void;

    public function update(Category $category): void;

    public function remove(Category $category): void;

    public function findById(string $id): ?Category;

}
