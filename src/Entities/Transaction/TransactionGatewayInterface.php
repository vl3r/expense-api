<?php

declare(strict_types=1);

namespace App\Entities\Transaction;

use ArtoxLab\Bundle\ClarcBundle\Core\UseCases\Interfaces\PaginatorInterface;
use DateTime;

interface TransactionGatewayInterface
{
    public function add(Transaction $transaction): void;

    public function update(Transaction $transaction): void;

    public function remove(Transaction $transaction): void;

    public function findById(string $id): ?Transaction;

    /**
     * @return PaginatorInterface<Transaction>
     */
    public function findTransactionsByTimePeriod(
        string $userId,
        ?DateTime $dateFrom,
        ?DateTime $dateTo,
        int $page,
        int $limit
    ): PaginatorInterface;
}
