<?php

declare(strict_types=1);

namespace App\Interfaces\UI\API\Controllers;

use App\Interfaces\UI\API\Transformers\User\UserTransformer;
use App\UseCases\Queries;
use ArtoxLab\Bundle\ClarcBundle\Core\Interfaces\UI\API\Controllers\AbstractApiController;
use Nelmio\ApiDocBundle\Annotation\Security;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class MeController extends AbstractApiController
{
    #[Route('/api/v1/me', name: 'show_identity', methods: ['GET'])]
    #[\OpenApi\Attributes\Tag(name: 'Me')]
    #[\OpenApi\Attributes\Response(
        response: '200',
        description: 'Ok.',
        content: new \OpenApi\Attributes\JsonContent(
            properties: [new \OpenApi\Attributes\Property(property: 'data', ref: '#/components/schemas/UserModel')]
        )
    )]
    #[\OpenApi\Attributes\Response(
        response: '403',
        description: 'Access denied exception.',
        content: new \OpenApi\Attributes\JsonContent(ref: '#/components/schemas/AccessDeniedErrorModel')
    )]
    #[Security(name: 'Bearer')]
    public function showUser(UserTransformer $transformer): Response
    {
        $user = $this->queryBus->query(new Queries\Me\Get\Command());

        return $this->json($this->transform($user, $transformer));
    }
}
