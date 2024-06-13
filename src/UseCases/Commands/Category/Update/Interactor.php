<?php

declare(strict_types=1);

namespace App\UseCases\Commands\Category\Update;

use App\Entities\Category\CategoryGatewayInterface;
use App\Entities\Exceptions\Category\CategoryNotFoundException;
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

    public function __invoke(Command $command): void
    {
        $user = $this->userStorage->getUser();

        if ($user === null) {
            throw new UserNotFoundException();
        }

        $category = $this->categoryGateway->findById($command->id);

        if ($category === null) {
            throw new CategoryNotFoundException();
        }

        $category->changeName($command->name);

        $this->categoryGateway->update($category);
    }
}
