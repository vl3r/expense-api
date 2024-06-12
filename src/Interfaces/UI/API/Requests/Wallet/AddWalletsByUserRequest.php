<?php

declare(strict_types=1);

namespace App\Interfaces\UI\API\Requests\Wallet;

use ArtoxLab\Bundle\ClarcBundle\Core\Interfaces\UI\API\Requests\AbstractRequest;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\Constraints as Assert;

final class

AddWalletsByUserRequest extends AbstractRequest
{

    /**
     * @return array<string, list<Constraint>>
     */
    public function getRules(): array
    {
        return [
            'name' => [
                new Assert\NotBlank(),
                new Assert\Type('string')
            ],
        ];
    }
    public function getName (): string
    {
      return (string) $this->params['name'];
    }
}