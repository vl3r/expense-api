<?php

declare(strict_types=1);

namespace App\Entities\User;

interface UserGatewayInterface
{
    public function findByEmail(string $email): ?User;
}
