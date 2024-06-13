<?php

declare(strict_types=1);

namespace App\Entities\Exceptions\Category;

use ArtoxLab\Bundle\ClarcBundle\Core\Entity\Exceptions\DomainHttpException;
use Symfony\Component\HttpFoundation\Response;

final class CategoryNotFoundException extends DomainHttpException
{
    public function __construct()
    {
        parent::__construct('The category was not found.',Response::HTTP_NOT_FOUND);
    }

}
