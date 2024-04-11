<?php

declare(strict_types=1);

namespace App\Interfaces\UI\API\Transformers\User;

use App\Entities\DTO\User\UserDTO;
use League\Fractal\TransformerAbstract;

final class UserTransformer extends TransformerAbstract
{
    /**
     * @return array<string, mixed>
     */
    public function transform(UserDTO $userDTO): array
    {
        return [
            'id'    => $userDTO->id,
            'email' => $userDTO->email,
        ];
    }
}
