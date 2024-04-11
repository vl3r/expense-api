<?php

declare(strict_types=1);

namespace App\Entities\Exceptions\User;

use ArtoxLab\Bundle\ClarcBundle\Core\Entity\Exceptions\DomainHttpException;
use Symfony\Component\HttpFoundation\Response;

final class UserNotFoundException extends DomainHttpException
{
    public function __construct()
    {
        parent::__construct('User not found.', Response::HTTP_NOT_FOUND);
    }
}
