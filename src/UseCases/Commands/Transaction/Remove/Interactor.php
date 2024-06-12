<?php

declare(strict_types=1);

namespace App\UseCases\Commands\Transaction\Remove;
use App\Entities\Exceptions\Transaction\TransactionNotFoundException;
use App\Entities\Exceptions\User\UserNotFoundException;
use App\Entities\Exceptions\Wallet\WalletNotFoundException;
use App\Entities\Transaction\TransactionGatewayInterface;
use App\Entities\User\UserStorageInterface;
use App\Entities\Wallet\WalletGatewayInterface;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler(bus: 'command.bus', fromTransport: 'sync')]
final readonly class Interactor
{
    public function __construct(
        private UserStorageInterface $userStorage,
        private TransactionGatewayInterface $transactionGateway,
        private WalletGatewayInterface $walletGateway,
    ){
    }
    public function __invoke(Command $command): void
    {
        $user = $this->userStorage->getUser();

        if ($user === null) {
            throw new UserNotFoundException();
        }

        $transaction = $this->transactionGateway->findById($command->id);

        if ($transaction === null) {
            throw new TransactionNotFoundException();
        }

        $wallet = $this->walletGateway->findById($transaction->getWalletId());

        if ($wallet === null || $wallet->getUserId() !== $user->getId()) {
            throw new WalletNotFoundException();
        }

        $this->transactionGateway->remove($transaction);
    }
}
