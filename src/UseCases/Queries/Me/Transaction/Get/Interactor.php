<?php

declare(strict_types=1);

namespace App\UseCases\Queries\Me\Transaction\Get;

use App\Entities\DTO\Transaction\TransactionDTO;
use App\Entities\Exceptions\User\UserNotFoundException;
use App\Entities\Transaction\Transaction;
use App\Entities\Transaction\TransactionGatewayInterface;
use App\Entities\User\UserStorageInterface;
use ArtoxLab\Bundle\ClarcBundle\Core\UseCases\Interfaces\PaginatorInterface;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler(bus: 'query.bus', fromTransport: 'sync')]
final readonly class Interactor
{
    public function __construct(
        private UserStorageInterface $userStorage,
        private TransactionGatewayInterface $transactionGateway
    ) {
    }

    public function __invoke(Command $command): PaginatorInterface
    {
        $user = $this->userStorage->getUser();

        if ($user === null) {
            throw new UserNotFoundException();
        }

        $transactions = $this->transactionGateway->findTransactionsByTimePeriod(
            $user->getId(),
            $command->dateFrom,
            $command->dateTo,
            $command->page,
            $command->limit
        );

        $transactions->map(static fn(Transaction $transaction): TransactionDTO => $transaction->toDTO());

        return $transactions;
    }
}
