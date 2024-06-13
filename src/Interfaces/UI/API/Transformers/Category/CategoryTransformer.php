<?php

declare(strict_types=1);

namespace App\Interfaces\UI\API\Transformers\Category;

use App\Entities\DTO\Category\CategoryDTO;
use League\Fractal\TransformerAbstract;

final class CategoryTransformer extends TransformerAbstract
{
    public function transform(CategoryDTO $categoryDTO): array
    {
        return [
            'id'   => $categoryDTO->id,
            'name' => $categoryDTO->name,
        ];
    }

}
