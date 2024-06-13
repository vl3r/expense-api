<?php

declare(strict_types=1);

namespace App\UseCases\Commands\Category\Remove;

use App\Entities\Category\CategoryGatewayInterface;
use App\Entities\Exceptions\Category\CategoryNotFoundException;
use App\Entities\Exceptions\User\UserNotFoundException;
use App\Entities\Exceptions\Wallet\WalletNotFoundException;
use App\Entities\User\UserStorageInterface;
use App\Entities\Wallet\WalletGatewayInterface;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler(bus: 'command.bus', fromTransport: 'sync')]
final readonly class Interactor
{
    public function __construct(
        private UserStorageInterface $userStorage,
        private CategoryGatewayInterface $categoryGateway,
    ){
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

        $this->categoryGateway->remove($category);
    }
}
