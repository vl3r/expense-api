<?php

declare(strict_types=1);

namespace App\Entities\Exceptions\Wallet;

use ArtoxLab\Bundle\ClarcBundle\Core\Entity\Exceptions\DomainHttpException;
use Symfony\Component\HttpFoundation\Response;
class WalletNotFoundException extends DomainHttpException
{
    public function __construct()
    {
        parent::__construct('Wallet not found.', Response::HTTP_NOT_FOUND);
    }
}
