<?php

declare(strict_types=1);

namespace App\Interfaces\UI\API\Requests\Transaction;

use App\Entities\Services\Converter\DTO\PriceDTO;
use App\Interfaces\Validators\Constraints\RequiredUuid4;
use ArtoxLab\Bundle\ClarcBundle\Core\Interfaces\UI\API\Requests\AbstractRequest;
use DateTimeImmutable;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\Constraints as Assert;

final class UpdateTransactionByUserRequest extends AbstractRequest
{
    /**
     * @return array<string, list<Constraint>>
     */
    public function getRules(): array
    {
        return [
            'id'       => [
                new RequiredUuid4(),
            ],
            'committed_at' => [
                new Assert\Date(),
                new Assert\NotBlank(),
            ],
            'amount'   => [
                new Assert\Type('numeric'),
                new Assert\NotBlank(),
            ],
            'currency' => [
                new Assert\Type('string'),
                new Assert\Length(3),
                new Assert\NotBlank(),
            ],
        ];
    }

    public function getId(): string
    {
        return (string) $this->params['id'];
    }

    public function getCommitedAt(): DateTimeImmutable
    {
        return new DateTimeImmutable($this->params['committed_at']);
    }

    public function getAmount(): PriceDTO
    {
        return new PriceDTO((string) $this->params['amount'], $this->params['currency']);
    }
}
