<?php

declare(strict_types=1);

namespace App\Entities\Wallet;

interface WalletGatewayInterface
{
    /**
     * @return array<Wallet>
     */
    public function findWalletsByUserId(string $userId): array;
}
