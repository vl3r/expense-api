<?php

declare(strict_types=1);

namespace App\Entities\Services\Converter\DTO;

final class PriceDTO
{
    public function __construct(public string $amount, public string $currency)
    {
    }
}
