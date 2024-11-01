<?php

declare(strict_types=1);

namespace App\Interfaces\UI\API\Requests\Transaction;

use App\Interfaces\Constants\Numeric;
use ArtoxLab\Bundle\ClarcBundle\Core\Interfaces\UI\API\Requests\AbstractRequest;
use DateTime;
use Exception;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\Constraints as Assert;

final class FindUserTransactionRequest extends AbstractRequest
{
    const int DEFAULT_LIMIT = 10;

    /**
     * @return array<string, list<Constraint>>
     */
    public function getRules(): array
    {
        return [
            'date_from' => [
                new Assert\Date(),
            ],
            'date_to'   => [
                new Assert\Date(),
            ],
            'page'      => [
                new Assert\Type('numeric'),
                new Assert\Positive(),
                new Assert\LessThan(Numeric::DB_INT_MAX),
            ],
            'limit'     => [
                new Assert\Type('numeric'),
                new Assert\Positive(),
                new Assert\LessThan(100),
            ],
        ];

    }


    /**
     * @throws Exception
     */
    public function getDateFrom(): ?DateTime
    {
        if (isset($this->params['date_from'])) {
            return new DateTime($this->params['date_from']);
        }

        return null;
    }

    /**
     * @throws Exception
     */

    public function getDateTo(): ?DateTime
    {
        if (isset($this->params['date_to'])) {
            return new DateTime($this->params['date_to']);
        }

        return null;
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
