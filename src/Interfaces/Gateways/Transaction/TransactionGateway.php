<?php

declare(strict_types=1);

namespace App\Interfaces\Gateways\Transaction;

use App\Entities\Transaction\Transaction;
use App\Entities\Transaction\TransactionGatewayInterface;
use App\Entities\Wallet\Wallet;
use App\Interfaces\Helpers\QueryHelper;
use ArtoxLab\Bundle\ClarcBundle\Core\Interfaces\Gateways\Paginator;
use ArtoxLab\Bundle\ClarcBundle\Core\UseCases\Interfaces\PaginatorInterface;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Tools\Pagination\Paginator as ORMPaginator;

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

    /**
     * @return PaginatorInterface<Transaction>
     */
    public function findTransactionsByTimePeriod(
        string $userId,
        ?DateTime $dateFrom,
        ?DateTime $dateTo,
        int $page,
        int $limit
    ): PaginatorInterface {
        {
            $qb = $this->em->createQueryBuilder();

            $transactionQuery = $qb
                ->select('transaction')
                ->from(Transaction::class, 'transaction')
                ->join(Wallet::class, 'wallet', 'WITH', 'transaction.walletId = wallet.id')
                ->where('wallet.userId = :userId')
                ->orderBy('transaction.committedAt', 'DESC')
                ->setParameter('userId', $userId);

            if ($dateFrom !== null) {
                $transactionQuery
                    ->andWhere('transaction.committedAt >= :dateFrom')
                    ->setParameter('dateFrom', $dateFrom);
            }

            if ($dateTo !== null) {
                $transactionQuery
                    ->andWhere('transaction.committedAt <= :dateTo')
                    ->setParameter('dateTo', $dateTo);
            }

            $ormPaginator = new ORMPaginator($transactionQuery);
            $query        = $ormPaginator
                ->getQuery()
                ->setFirstResult(QueryHelper::offset($page, $limit))
                ->setMaxResults($limit);

            return new Paginator(
                $page,
                $limit,
                $query->getResult(),
                $ormPaginator->count(),
            );
        }
    }
}
