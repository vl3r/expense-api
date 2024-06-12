<?php

declare(strict_types=1);

namespace App\UseCases\Commands\Transaction\Update;

use App\Entities\Exceptions\Transaction\TransactionNotFoundException;
use App\Entities\Exceptions\User\UserNotFoundException;
use App\Entities\Exceptions\Wallet\WalletNotFoundException;
use App\Entities\Services\Converter\PriceConverterInterface;
use App\Entities\Transaction\TransactionGatewayInterface;
use App\Entities\User\UserStorageInterface;
use App\Entities\Wallet\WalletGatewayInterface;
use DateTimeImmutable;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler(bus: 'command.bus', fromTransport: 'sync')]
final readonly class Interactor
{
    public function __construct(
        private UserStorageInterface $userStorage,
        private TransactionGatewayInterface $transactionGateway,
        private WalletGatewayInterface $walletGateway,
        private PriceConverterInterface $priceConverter,
    ) {
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

        $amount = $this->priceConverter->price2money($command->amount);

        if (
            $transaction->getCommittedAt() === $command->commitedAt
            && $transaction->getAmount()->equals($amount) === true
        ) {
            return;
        }

        $transaction->changeAmount($amount);
        $transaction->changeCommittedAt($command->commitedAt);

        $this->transactionGateway->update($transaction);
    }
}
