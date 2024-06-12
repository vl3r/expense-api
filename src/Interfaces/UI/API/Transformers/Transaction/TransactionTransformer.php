<?php

declare(strict_types=1);

namespace App\Interfaces\UI\API\Transformers\Transaction;

use App\Entities\DTO\Transaction\TransactionDTO;
use App\Entities\Services\Converter\PriceConverterInterface;
use DateTimeInterface;
use League\Fractal\TransformerAbstract;

final class TransactionTransformer extends TransformerAbstract
{
    public function __construct(private readonly PriceConverterInterface $priceConverter)
    {
    }

    public function transform(TransactionDTO $transactionDTO): array
    {
        $amount = $this->priceConverter->money2price($transactionDTO->amount);

        return [
            'id'           => $transactionDTO->id,
            'wallet_id'    => $transactionDTO->walletId,
            'amount'       => $amount->amount,
            'currency'     => $amount->currency,
            'committed_at' => $transactionDTO->committedAt->format('Y-m-d'),
        ];
    }
}


