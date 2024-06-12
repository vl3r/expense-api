<?php

declare(strict_types=1);

namespace App\Entities\Exceptions\Transaction;

use ArtoxLab\Bundle\ClarcBundle\Core\Entity\Exceptions\DomainHttpException;
use Symfony\Component\HttpFoundation\Response;

final class TransactionNotFoundException extends DomainHttpException
{
    public function __construct()
    {
        parent::__construct('The transaction was not found.',Response::HTTP_NOT_FOUND);
    }

}
