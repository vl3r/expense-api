<?php

declare(strict_types=1);

namespace App\Entities\Services\Converter;

use App\Entities\Services\Converter\DTO\PriceDTO;
use Money\Money;

interface PriceConverterInterface
{
    public function money2price(Money $money): PriceDTO;

    public function price2money(PriceDTO $price): Money;

    public function money2amount(Money $money): float;
}
