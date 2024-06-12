<?php

declare(strict_types=1);

namespace App\Entities\Transaction;


interface TransactionGatewayInterface
{
    public function add(Transaction $transaction): void;

    public function update(Transaction $transaction): void;

    public function remove(Transaction $transaction): void;

    public function findById(string $id): ?Transaction;

}
