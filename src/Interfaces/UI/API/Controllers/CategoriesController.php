<?php

declare(strict_types=1);

namespace App\Interfaces\UI\API\Controllers;

use App\Interfaces\UI\API\Requests\Category\AddCategoryByTransactionRequest;
use App\Interfaces\UI\API\Requests\Category\RemoveCategoryByTransactionRequest;
use App\Interfaces\UI\API\Requests\Category\UpdateCategoryByTransactionRequest;
use App\Interfaces\UI\API\Transformers\Category\CategoryTransformer;
use ArtoxLab\Bundle\ClarcBundle\Core\Interfaces\UI\API\Controllers\AbstractApiController;
use Nelmio\ApiDocBundle\Annotation\Security;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class CategoriesController extends AbstractApiController
{
    #[Route('/api/v1/me/wallets/transactions/categories', name: 'category_add', methods: ['POST'])]
    #[\OpenApi\Attributes\Tag(name: 'Categories')]
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
        content: new \OpenApi\Attributes\JsonContent(ref: '#/components/schemas/CategoryModel')
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
    public function addCategory(AddCategoryByTransactionRequest $request, CategoryTransformer $transformer): Response
    {
        $command = new \App\UseCases\Commands\Category\Add\Command($request->getName());

        $category = $this->commandBus->execute($command);

        return $this->created($this->transform($category, $transformer));
    }

    #[Route('/api/v1/me/wallets/transactions/categories/{id}', name: 'category_change', methods: ['PUT'])]
    #[\OpenApi\Attributes\Tag(name: 'Categories')]
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
    public function updateCategory(UpdateCategoryByTransactionRequest $request): Response
    {
        $command = new \App\UseCases\Commands\Category\Update\Command(
            $request->getId(),
            $request->getName()
        );
        $this->commandBus->execute($command);

        return $this->noContent();
    }

    #[Route('/api/v1/me/wallets/transactions/categories/{id}', name: 'category_remove', methods: ['DELETE'])]
    #[\OpenApi\Attributes\Tag(name: 'Categories')]
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
    public function removeCategory(RemoveCategoryByTransactionRequest $request): Response
    {
        $command = new \App\UseCases\Commands\Category\Remove\Command(
            $request->getId()
        );
        $this->commandBus->execute($command);

        return $this->noContent();
    }
}
