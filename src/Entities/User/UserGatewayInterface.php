<?php

declare(strict_types=1);

namespace App\Entities\User;

use App\Entities\Wallet\Wallet;

interface UserGatewayInterface
{
    public function findByEmail(string $email): ?User;

}
