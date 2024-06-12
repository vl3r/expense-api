<?php

declare(strict_types=1);

namespace App\Entities\Wallet;

use ArtoxLab\Bundle\ClarcBundle\Core\UseCases\Interfaces\PaginatorInterface;

interface WalletGatewayInterface
{
    /**
     * @return PaginatorInterface<Wallet>
     */
    public function findWalletsByUserId(string $userId, int $page, int $limit): PaginatorInterface;

    public function add(Wallet $wallet): void;

    public function update(Wallet $wallet): void;

    public function findById(string $id): ?Wallet;
}
