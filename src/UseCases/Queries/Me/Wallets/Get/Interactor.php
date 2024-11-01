<?php

declare(strict_types=1);

namespace App\UseCases\Queries\Me\Wallets\Get;

use App\Entities\DTO\Wallet\WalletDTO;
use App\Entities\Exceptions\User\UserNotFoundException;
use App\Entities\User\UserStorageInterface;
use App\Entities\Wallet\Wallet;
use App\Entities\Wallet\WalletGatewayInterface;
use ArtoxLab\Bundle\ClarcBundle\Core\UseCases\Interfaces\PaginatorInterface;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler(bus: 'query.bus', fromTransport: 'sync')]
final readonly class Interactor
{
    public function __construct(
        private UserStorageInterface $userStorage,
        private WalletGatewayInterface $walletsGateway,
    ) {
    }

    /**
     * @return PaginatorInterface<WalletDTO>
     */
    public function __invoke(Command $command): PaginatorInterface
    {
        $user = $this->userStorage->getUser();

        if ($user === null) {
            throw new UserNotFoundException();
        }

        $wallets = $this->walletsGateway->findWalletsByUserId($user->getId(), $command->page, $command->limit);

        $wallets->map(static fn(Wallet $wallet): WalletDTO => $wallet->toDTO());

        return $wallets;
    }
}
