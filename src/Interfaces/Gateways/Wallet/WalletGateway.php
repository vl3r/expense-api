<?php

declare(strict_types=1);

namespace App\Interfaces\Gateways\Wallet;

use App\Entities\Wallet\Wallet;
use App\Entities\Wallet\WalletGatewayInterface;
use App\Interfaces\Helpers\QueryHelper;
use ArtoxLab\Bundle\ClarcBundle\Core\Interfaces\Gateways\Paginator;
use ArtoxLab\Bundle\ClarcBundle\Core\UseCases\Interfaces\PaginatorInterface;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Tools\Pagination\Paginator as ORMPaginator;

final readonly class WalletGateway implements WalletGatewayInterface
{
    public function __construct(private EntityManagerInterface $em)
    {
    }

    /**
     * @return PaginatorInterface<Wallet>
     */
    public function findWalletsByUserId(string $userId, int $page, int $limit): PaginatorInterface
    {
        $query = $this->em->createQueryBuilder()
            ->select('wallet')
            ->from(Wallet::class, 'wallet')
            ->where('wallet.userId = :userId')
            ->orderBy('wallet.name', 'ASC')
            ->setParameter('userId', $userId)
            ->setFirstResult(QueryHelper::offset($page, $limit))
            ->setMaxResults($limit)
            ->getQuery();


        $paginator = new ORMPaginator($query);

        return new Paginator(
            $page,
            $limit,
            $query->getResult(),
            $paginator->count(),
        );

    }
    public function findById(string $id): ?Wallet
    {
        return $this->em->getRepository(Wallet::class)->find($id);
    }

    public function add(Wallet $wallet): void
    {
        $this->em->persist($wallet);
        $this->em->flush();
    }

    public function update(Wallet $wallet): void
    {
       $this->em->persist($wallet);
       $this->em->flush();
    }
}
