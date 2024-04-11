<?php

declare(strict_types=1);

namespace App\Interfaces\Gateways\User;

use App\Entities\User\User;
use App\Entities\User\UserGatewayInterface;
use Doctrine\ORM\EntityManagerInterface;

final class UserGateway implements UserGatewayInterface
{
    public function __construct(private EntityManagerInterface $em)
    {
    }

    public function findByEmail(string $email): ?User
    {
        return $this->em->getRepository(User::class)->findOneBy([
            'email' => $email,
        ]);
    }
}
