<?php

declare(strict_types=1);

namespace App\Interfaces\UI\API\Transformers\Wallet;

use App\Entities\DTO\Wallet\WalletDTO;
use League\Fractal\TransformerAbstract;

final class WalletTransformer extends TransformerAbstract
{
    public function transform(WalletDTO $walletDTO): array
    {
        return [
            'id'      => $walletDTO->id,
            'name'    => $walletDTO->name,
            'user_id' => $walletDTO->userId,
        ];
    }
}
