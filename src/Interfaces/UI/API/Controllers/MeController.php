<?php

declare(strict_types=1);

namespace App\Interfaces\UI\API\Controllers;

use App\Interfaces\UI\API\Requests\Transaction\AddTransactionByUserRequest;
use App\Interfaces\UI\API\Requests\Transaction\RemoveTransactionByUserRequest;
use App\Interfaces\UI\API\Requests\Transaction\UpdateTransactionByUserRequest;
use App\Interfaces\UI\API\Requests\User\FindUserWalletsRequest;
use App\Interfaces\UI\API\Requests\Wallet\AddWalletsByUserRequest;
use App\Interfaces\UI\API\Requests\Wallet\UpdateWalletsByUserRequest;
use App\Interfaces\UI\API\Transformers\Transaction\TransactionTransformer;
use App\Interfaces\UI\API\Transformers\User\UserTransformer;
use App\Interfaces\UI\API\Transformers\Wallet\WalletTransformer;
use App\UseCases\Commands\Wallet\Add\Command;
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
    #[\OpenApi\Attributes\Parameter(
        name: 'page',
        description: 'List page',
        in: 'query',
        required: false,
        schema: new \OpenApi\Attributes\Schema(type: 'integer'),
        example: 1
    )]
    #[\OpenApi\Attributes\Parameter(
        name: 'limit',
        description: 'Items per page',
        in: 'query',
        required: false,
        schema: new \OpenApi\Attributes\Schema(type: 'integer'),
        example: 10
    )]
    #[\OpenApi\Attributes\Response(
        response: Response::HTTP_OK,
        description: 'Ok.',
        content: new \OpenApi\Attributes\JsonContent(
            properties: [
                new \OpenApi\Attributes\Property(
                    property: 'data',
                    type: 'array',
                    items: new \OpenApi\Attributes\Items(ref: '#/components/schemas/WalletModel')
                ),
                new \OpenApi\Attributes\Property(
                    property: 'meta',
                    properties: [
                        new \OpenApi\Attributes\Property(
                            property: 'paginator',
                            ref: '#/components/schemas/PaginationModel'
                        ),
                    ]
                ),
            ]
        )
    )]
    #[\OpenApi\Attributes\Response(
        response: Response::HTTP_FORBIDDEN,
        description: 'Access denied exception.',
        content: new \OpenApi\Attributes\JsonContent(ref: '#/components/schemas/AccessDeniedErrorModel')
    )]
    #[Security(name: 'Bearer')]
    public function findUserWallets(
        FindUserWalletsRequest $request,
        WalletTransformer $transformer
    ): Response {
        $paginator = $this->queryBus->query(new Queries\Me\Wallets\Get\Command(
            page: $request->page(),
            limit: $request->limit(),
        ));

        return $this->json($this->transform($paginator, $transformer));

    }

    #[Route('/api/v1/me/wallets', name: 'wallet_add', methods: ['POST'])]
    #[\OpenApi\Attributes\Tag(name: 'Wallet')]
    #[\OpenApi\Attributes\RequestBody(
        required: true,
        content: new \OpenApi\Attributes\JsonContent(
            properties: [
                new \OpenApi\Attributes\Property(
                    property: 'name',
                    type: 'string',
                    example: 'Phone'
                ),
            ]
        )
    )]
    #[\OpenApi\Attributes\Response(
        response: Response::HTTP_CREATED,
        description: 'Created.',
        content: new \OpenApi\Attributes\JsonContent(
            properties: [new \OpenApi\Attributes\Property(property: 'data', ref: '#/components/schemas/WalletModel')]
        )
    )]
    #[\OpenApi\Attributes\Response(
        response: Response::HTTP_FORBIDDEN,
        description: 'Access denied exception.',
        content: new \OpenApi\Attributes\JsonContent(ref: '#/components/schemas/AccessDeniedErrorModel')
    )]
    #[\OpenApi\Attributes\Response(
        response: Response::HTTP_UNPROCESSABLE_ENTITY,
        description: 'Validation exception.',
        content: new \OpenApi\Attributes\JsonContent(ref: '#/components/schemas/ValidationErrorModel')
    )]
    #[Security(name: 'Bearer')]
    public function addUserWallet(AddWalletsByUserRequest $request, WalletTransformer $transformer): Response
    {
        $command = new Command($request->getName());

        $wallet = $this->commandBus->execute($command);

        return $this->created($this->transform($wallet, $transformer));
    }

    #[Route('/api/v1/me/wallets/{id}', name: 'wallet_change', methods: ['PUT'])]
    #[\OpenApi\Attributes\Tag(name: 'Wallet')]
    #[\OpenApi\Attributes\Parameter(
        name: 'id',
        in: 'path',
        required: true,
        schema: new \OpenApi\Attributes\Schema(type: 'string', format: 'uuid')
    )]
    #[\OpenApi\Attributes\RequestBody(
        required: true,
        content: new \OpenApi\Attributes\JsonContent(
            properties: [
                new \OpenApi\Attributes\Property(
                    property: 'name',
                    type: 'string',
                    example: 'Phone'
                ),
            ]
        )
    )]
    #[\OpenApi\Attributes\Response(
        response: Response::HTTP_NO_CONTENT,
        description: 'No content. Entity successfully updated.')]
    #[\OpenApi\Attributes\Response(
        response: Response::HTTP_FORBIDDEN,
        description: 'Access denied exception.',
        content: new \OpenApi\Attributes\JsonContent(ref: '#/components/schemas/AccessDeniedErrorModel')
    )]
    #[\OpenApi\Attributes\Response(
        response: Response::HTTP_UNPROCESSABLE_ENTITY,
        description: 'Validation exception.',
        content: new \OpenApi\Attributes\JsonContent(ref: '#/components/schemas/ValidationErrorModel')
    )]
    #[Security(name: 'Bearer')]
    public function updateWallet(UpdateWalletsByUserRequest $request): Response
    {
        $command = new \App\UseCases\Commands\Wallet\Update\Command(
            $request->getId(),
            $request->getName()
        );
        $this->commandBus->execute($command);

        return $this->noContent();
    }

    #[Route('/api/v1/me/wallets/transactions', name: 'transaction_add', methods: ['POST'])]
    #[\OpenApi\Attributes\Tag(name: 'Transactions')]
    #[\OpenApi\Attributes\RequestBody(
        required: true,
        content: new \OpenApi\Attributes\JsonContent(
            properties: [
                new \OpenApi\Attributes\Property(
                    property: 'wallet_id',
                    type: 'string',
                    format: 'uuid'
                ),
                new \OpenApi\Attributes\Property(
                    property: 'committed_at',
                    type: 'string',
                    example: '2000-01-01'
                ),
                new \OpenApi\Attributes\Property(
                    property: 'amount',
                    type: 'numeric',
                    example: '150.00'
                ),
                new \OpenApi\Attributes\Property(
                    property: 'currency',
                    type: 'string',
                    example: 'BYN'
                ),
            ]
        )
    )]
    #[\OpenApi\Attributes\Response(
        response: Response::HTTP_CREATED,
        description: 'Created.',
        content: new \OpenApi\Attributes\JsonContent(
            properties: [new \OpenApi\Attributes\Property(property: 'data', ref: '#/components/schemas/TransactionsModel')]
        )
    )]
    #[\OpenApi\Attributes\Response(
        response: Response::HTTP_UNPROCESSABLE_ENTITY,
        description: 'Validation exception.',
        content: new \OpenApi\Attributes\JsonContent(ref: '#/components/schemas/ValidationErrorModel')
    )]
    #[Security(name: 'Bearer')]
    public function addTransaction(
        AddTransactionByUserRequest $request, TransactionTransformer $transformer,
    ): Response {
        $command     = new \App\UseCases\Commands\Transaction\Add\Command(
            $request->getWalletId(),
            $request->getCommitedAt(),
            $request->getAmount(),
        );
        $transaction = $this->commandBus->execute($command);

        return $this->created($this->transform($transaction, $transformer));
    }

    #[Route('/api/v1/me/wallets/transactions/{id}', name: 'transaction_change', methods: ['PUT'])]
    #[\OpenApi\Attributes\Tag(name: 'Transactions')]
    #[\OpenApi\Attributes\Parameter(
        name: 'id',
        in: 'path',
        required: true,
        schema: new \OpenApi\Attributes\Schema(type: 'string', format: 'uuid')
    )]
    #[\OpenApi\Attributes\RequestBody(
        required: true,
        content: new \OpenApi\Attributes\JsonContent(
            properties: [
                new \OpenApi\Attributes\Property(
                    property: 'committed_at',
                    type: 'string',
                    example: '2000-01-01'
                ),
                new \OpenApi\Attributes\Property(
                    property: 'amount',
                    type: 'numeric',
                    example: '150.00'
                ),
                new \OpenApi\Attributes\Property(
                    property: 'currency',
                    type: 'string',
                    example: 'BYN'
                ),
            ]
        )
    )]
    #[\OpenApi\Attributes\Response(
        response: Response::HTTP_NO_CONTENT,
        description: 'No content. Entity successfully updated.')]
    #[\OpenApi\Attributes\Response(
        response: Response::HTTP_FORBIDDEN,
        description: 'Access denied exception.',
        content: new \OpenApi\Attributes\JsonContent(ref: '#/components/schemas/AccessDeniedErrorModel')
    )]
    #[\OpenApi\Attributes\Response(
        response: Response::HTTP_UNPROCESSABLE_ENTITY,
        description: 'Validation exception.',
        content: new \OpenApi\Attributes\JsonContent(ref: '#/components/schemas/ValidationErrorModel')
    )]
    #[Security(name: 'Bearer')]
    public function changeTransaction(UpdateTransactionByUserRequest $request): Response {
        $command = new \App\UseCases\Commands\Transaction\Update\Command(
            $request->getId(),
            $request->getCommitedAt(),
            $request->getAmount()
        );

        $this->commandBus->execute($command);

        return $this->noContent();
    }

    #[Route('/api/v1/me/wallets/transactions/{id}', name: 'transaction_remove', methods: ['DELETE'])]
    #[\OpenApi\Attributes\Tag(name: 'Transactions')]
    #[\OpenApi\Attributes\Parameter(
        name: 'id',
        in: 'path',
        required: true,
        schema: new \OpenApi\Attributes\Schema(type: 'string', format: 'uuid')
    )]
    #[\OpenApi\Attributes\Response(
        response: Response::HTTP_NO_CONTENT,
        description: 'No content. Entity successfully deleted.')]
    #[\OpenApi\Attributes\Response(
        response: Response::HTTP_FORBIDDEN,
        description: 'Access denied exception.',
        content: new \OpenApi\Attributes\JsonContent(ref: '#/components/schemas/AccessDeniedErrorModel')
    )]
    #[\OpenApi\Attributes\Response(
        response: Response::HTTP_UNPROCESSABLE_ENTITY,
        description: 'Validation exception.',
        content: new \OpenApi\Attributes\JsonContent(ref: '#/components/schemas/ValidationErrorModel')
    )]
    #[Security(name: 'Bearer')]
    public function removeTransaction(RemoveTransactionByUserRequest $request): Response
    {
        $command = new \App\UseCases\Commands\Transaction\Remove\Command(
            $request->getId()
        );
        $this->commandBus->execute($command);

        return $this->noContent();
    }
}
