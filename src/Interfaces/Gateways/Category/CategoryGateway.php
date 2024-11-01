<?php

declare(strict_types=1);

namespace App\Interfaces\Gateways\Category;

use App\Entities\Category\Category;
use App\Entities\Category\CategoryGatewayInterface;
use Doctrine\ORM\EntityManagerInterface;

final readonly class CategoryGateway implements CategoryGatewayInterface
{
    public function __construct(private EntityManagerInterface $em)
    {
    }

    public function add(Category $category): void
    {
        $this->em->persist($category);
        $this->em->flush();
    }

    public function update(Category $category): void
    {
        $this->em->persist($category);
        $this->em->flush();
    }

    public function remove(Category $category): void
    {
        $this->em->remove($category);
        $this->em->flush();
    }

    public function findById(string $id): ?Category
    {
        return $this->em->getRepository(Category::class)->find($id);
    }
}
