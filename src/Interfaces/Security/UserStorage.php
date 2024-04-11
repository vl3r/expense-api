<?php

declare(strict_types=1);

namespace App\Interfaces\Security;

use App\Entities\User\User;
use App\Entities\User\UserStorageInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

final class UserStorage implements UserStorageInterface
{
    public function __construct(private readonly TokenStorageInterface $tokenStorage)
    {
    }

    public function getUser(): ?User
    {
        $token = $this->tokenStorage->getToken();

        if ($token === null) {
            return null;
        }

        $user = $token->getUser();

        if (!$user instanceof User) {
            // e.g. anonymous authentication
            return null;
        }

        return $user;
    }
}
