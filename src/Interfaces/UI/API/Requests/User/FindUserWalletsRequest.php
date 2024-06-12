<?php

declare(strict_types=1);

namespace App\Interfaces\UI\API\Requests\User;

use App\Interfaces\Constants\Numeric;
use ArtoxLab\Bundle\ClarcBundle\Core\Interfaces\UI\API\Requests\AbstractRequest;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\Constraints as Assert;

final class FindUserWalletsRequest extends AbstractRequest
{
    private const int DEFAULT_LIMIT = 10;

    /**
     * @return array<string, list<Constraint>>
     */
    public function getRules(): array
    {
        return [
            'page'  => [
                new Assert\Type('numeric'),
                new Assert\Positive(),
                new Assert\LessThan(Numeric::DB_INT_MAX),
            ],
            'limit' => [
                new Assert\Type('numeric'),
                new Assert\Positive(),
                new Assert\LessThan(100),
            ],
        ];
    }
    public function page(): int
    {
        return (int) $this->params['page'] ?: 1;
    }

    public function limit(): int
    {
        return (int) $this->params['limit'] ?: self::DEFAULT_LIMIT;
    }
}
