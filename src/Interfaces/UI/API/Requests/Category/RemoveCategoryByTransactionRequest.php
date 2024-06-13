<?php

declare(strict_types=1);

namespace App\Interfaces\UI\API\Requests\Category;

use App\Interfaces\Validators\Constraints\RequiredUuid4;
use ArtoxLab\Bundle\ClarcBundle\Core\Interfaces\UI\API\Requests\AbstractRequest;
use Symfony\Component\Validator\Constraint;

final class RemoveCategoryByTransactionRequest extends AbstractRequest
{
    /**
     * @return array<string, list<Constraint>>
     */
    public function getRules(): array
    {
        return [
            'id' => [
                new RequiredUuid4(),
            ]
        ];
    }
    public function getId(): string
    {
        return (string) $this->params['id'];
    }
}
