<?php

declare(strict_types=1);

namespace App\Interfaces\Gateways\Wallet;

use App\Entities\Wallet\Wallet;
use App\Entities\Wallet\WalletGatewayInterface;
use Doctrine\ORM\EntityManagerInterface;

final readonly class WalletGateway implements WalletGatewayInterface
{
    public function __construct(private EntityManagerInterface $em)
    {
    }


    public function findWalletsByUserId(string $userId): array
    {
        return $this->em->getRepository(Wallet::class)->findBy([
            'userId' => $userId,
        ]);
    }
}
