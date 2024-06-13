<?php

declare(strict_types=1);

namespace App\UseCases\Commands\Category\Add;

use App\Entities\Category\Category;
use App\Entities\Category\CategoryGatewayInterface;
use App\Entities\DTO\Category\CategoryDTO;
use App\Entities\Exceptions\User\UserNotFoundException;
use App\Entities\User\UserStorageInterface;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler(bus: 'command.bus', fromTransport: 'sync')]
final readonly class Interactor
{
    public function __construct(
        private UserStorageInterface $userStorage,
        private CategoryGatewayInterface $categoryGateway,
    ) {
    }

    public function __invoke(Command $command): CategoryDTO
    {
        $user = $this->userStorage->getUser();

        if ($user === null) {
            throw new UserNotFoundException();
        }

        $category = new Category($command->name);
        $this->categoryGateway->add($category);


        return $category->toDTO();
    }

}
