<?php

declare(strict_types=1);

namespace App\Entities\User;

interface UserStorageInterface
{
    public function getUser(): ?User;
}
