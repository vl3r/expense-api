<?php

declare(strict_types=1);

namespace App\Interfaces\UI\API\Controllers;

use App\Interfaces\UI\API\Transformers\User\UserTransformer;
use App\Interfaces\UI\API\Transformers\Wallets\WalletTransformer;
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
        response: Response::HTTP_OK,
        description: 'Ok.',
        content: new \OpenApi\Attributes\JsonContent(
            properties: [new \OpenApi\Attributes\Property(property: 'data', ref: '#/components/schemas/UserModel')]
        )
    )]
    #[\OpenApi\Attributes\Response(
        response: Response::HTTP_FORBIDDEN,
        description: 'Access denied exception.',
        content: new \OpenApi\Attributes\JsonContent(ref: '#/components/schemas/AccessDeniedErrorModel')
    )]
    #[Security(name: 'Bearer')]
    public function showUser(UserTransformer $transformer): Response
    {
        $user = $this->queryBus->query(new Queries\Me\Get\Command());

        return $this->json($this->transform($user, $transformer));
    }

    #[Route('/api/v1/me/wallets', name: 'show_user_wallets', methods: ['GET'])]
    #[\OpenApi\Attributes\Tag(name: 'Me')]
    #[\OpenApi\Attributes\Response(
        response: Response::HTTP_OK,
        description: 'Ok.',
        content: new \OpenApi\Attributes\JsonContent(
            properties: [new \OpenApi\Attributes\Property(
                property: 'data',
                type: 'array',
                items: new \OpenApi\Attributes\Items(ref: '#/components/schemas/UserModel')
            )]
        )
    )]
    #[\OpenApi\Attributes\Response(
        response: Response::HTTP_FORBIDDEN,
        description: 'Access denied exception.',
        content: new \OpenApi\Attributes\JsonContent(ref: '#/components/schemas/AccessDeniedErrorModel')
    )]
    #[Security(name: 'Bearer')]
    public function findWallets(WalletTransformer $transformer): Response
    {
        $wallets = $this->queryBus->query(new Queries\Me\Wallets\Get\Command());

        return $this->json($this->transform($wallets, $transformer));
    }
}
