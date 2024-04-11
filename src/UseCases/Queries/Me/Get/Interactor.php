<?php

declare(strict_types=1);

namespace App\UseCases\Queries\Me\Get;

use App\Entities\DTO\User\UserDTO;
use App\Entities\Exceptions\User\UserNotFoundException;
use App\Entities\User\UserStorageInterface;
use ArtoxLab\Bundle\ClarcBundle\Core\UseCases\Commands\AbstractInteractor;

final class Interactor extends AbstractInteractor
{
    public function __construct(private UserStorageInterface $userStorage)
    {
    }

    public function __invoke(Command $command): UserDTO
    {
        $user = $this->userStorage->getUser();

        if ($user === null) {
            throw new UserNotFoundException();
        }

        return $user->toDTO();
    }
}
