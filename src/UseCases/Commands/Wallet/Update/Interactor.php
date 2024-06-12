<?php

declare(strict_types=1);

namespace App\UseCases\Commands\Wallet\Update;

use App\Entities\DTO\Wallet\WalletDTO;
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
        private WalletGatewayInterface $walletGateway
    ) {
    }

    public function __invoke(Command $command): WalletDTO
    {
        $user = $this->userStorage->getUser();

        if ($user === null) {
            throw new UserNotFoundException();
        }

        $wallet = $this->walletGateway->findById($command->id);

        if ($wallet === null) {
            throw new WalletNotFoundException();
        }

        if ($wallet->getUserId() !== $user->getId()) {
            throw new WalletNotFoundException();
        }

        $wallet->changeName($command->name);

        $this->walletGateway->update($wallet);

        return $wallet->toDTO();
    }
}
