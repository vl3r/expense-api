<?php

declare(strict_types=1);

namespace App\Interfaces\Security;

use App\Entities\User\User;
use App\Entities\User\UserGatewayInterface;
use Symfony\Component\Security\Core\Exception\UserNotFoundException;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;

final readonly class UserProvider implements UserProviderInterface
{
    public function __construct(private UserGatewayInterface $userGateway)
    {
    }

    public function refreshUser(UserInterface $user): UserInterface
    {
        return $user;
    }

    public function supportsClass(string $class): bool
    {
        return $class === User::class;
    }

    public function loadUserByIdentifier(string $identifier): UserInterface
    {
        $user = $this->userGateway->findByEmail($identifier);

        if (!$user instanceof User) {
            $exception = new UserNotFoundException();
            $exception->setUserIdentifier($identifier);

            throw $exception;
        }

        return $user;
    }
}
