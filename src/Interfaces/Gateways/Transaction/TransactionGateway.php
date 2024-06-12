<?php

declare(strict_types=1);

namespace App\Interfaces\Gateways\Transaction;

use App\Entities\Transaction\Transaction;
use App\Entities\Transaction\TransactionGatewayInterface;
use Doctrine\ORM\EntityManagerInterface;

final readonly class TransactionGateway implements TransactionGatewayInterface
{
    public function __construct(private EntityManagerInterface $em)
    {
    }
    public function add(Transaction $transaction): void
    {
        $this->em->persist($transaction);
        $this->em->flush();
    }
    public function update(Transaction $transaction): void
    {
        $this->em->persist($transaction);
        $this->em->flush();
    }
    public function remove(Transaction $transaction): void
    {
        $this->em->remove($transaction);
        $this->em->flush();
    }
    public function findById(string $id): ?Transaction
    {
        return $this->em->getRepository(Transaction::class)->find($id);
    }


}
