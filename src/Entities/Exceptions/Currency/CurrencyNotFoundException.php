<?php

declare(strict_types=1);

namespace App\Entities\Exceptions\Currency;

use ArtoxLab\Bundle\ClarcBundle\Core\Entity\Exceptions\DomainHttpException;
use Symfony\Component\HttpFoundation\Response;

final class CurrencyNotFoundException extends DomainHttpException
{
    public function __construct(string $code)
    {
        parent::__construct('Currency with code {code} not found.', Response::HTTP_NOT_FOUND);

        $this->setTranslationParameter('{code}', $code);
    }
}
