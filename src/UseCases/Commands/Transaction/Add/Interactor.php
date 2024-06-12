<?php

declare(strict_types=1);

namespace App\UseCases\Commands\Transaction\Add;

use App\Entities\DTO\Transaction\TransactionDTO;
use App\Entities\Exceptions\User\UserNotFoundException;
use App\Entities\Exceptions\Wallet\WalletNotFoundException;
use App\Entities\Services\Converter\PriceConverterInterface;
use App\Entities\Transaction\Transaction;
use App\Entities\Transaction\TransactionGatewayInterface;
use App\Entities\User\UserStorageInterface;
use App\Entities\Wallet\WalletGatewayInterface;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler(bus: 'command.bus', fromTransport: 'sync')]
final readonly class Interactor
{
    public function __construct(
        private UserStorageInterface $userStorage,
        private WalletGatewayInterface $walletGateway,
        private TransactionGatewayInterface $transactionGateway,
        private PriceConverterInterface $priceConverter,
    ) {
    }

    public function __invoke(Command $command): TransactionDTO
    {
        $user = $this->userStorage->getUser();

        if ($user === null) {
            throw new UserNotFoundException();
        }

        $wallet = $this->walletGateway->findById($command->walletId);

        if ($wallet === null || $wallet->getUserId() !== $user->getId()) {
            throw new WalletNotFoundException();
        }

        $transaction = new Transaction(
            walletId: $wallet->getId(),
            amount: $this->priceConverter->price2money($command->amount),
            committedAt: $command->commitedAt,
        );

        $this->transactionGateway->add($transaction);

        return $transaction->toDTO();
    }
}
