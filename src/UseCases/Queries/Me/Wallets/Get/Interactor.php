<?php

declare(strict_types=1);

namespace App\UseCases\Queries\Me\Wallets\Get;

use App\Entities\DTO\Wallet\WalletDTO;
use App\Entities\Exceptions\User\UserNotFoundException;
use App\Entities\User\UserStorageInterface;
use App\Entities\Wallet\Wallet;
use App\Entities\Wallet\WalletGatewayInterface;
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
     * @return array<WalletDTO>
     */
   public function __invoke(Command $command): array
   {
       $user = $this->userStorage->getUser();

       if ($user === null) {
           throw new UserNotFoundException();
       }

       $wallets = $this->walletsGateway->findWalletsByUserId($user->getId());

        return array_map(
            static fn (Wallet $wallet) => $wallet->toDTO(),
            $wallets
        );
   }
}
