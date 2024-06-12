<?php

declare(strict_types=1);

namespace App\Interfaces\Services\Converter;

use App\Entities\Exceptions\Currency\CurrencyNotFoundException;
use App\Entities\Services\Converter\DTO\PriceDTO;
use App\Entities\Services\Converter\PriceConverterInterface;
use Money\Currencies\ISOCurrencies;
use Money\Currency;
use Money\Exception\UnknownCurrencyException;
use Money\Formatter\DecimalMoneyFormatter;
use Money\Formatter\IntlMoneyFormatter;
use Money\Money;
use Money\Parser\DecimalMoneyParser;
use NumberFormatter;

final class PriceConverter implements PriceConverterInterface
{
    private ISOCurrencies $ISOCurrencies;

    private DecimalMoneyFormatter $decimalMoneyFormatter;

    private IntlMoneyFormatter $intlMoneyFormatter;

    /**
     * @param non-empty-string $defaultLocale
     * @param non-empty-string $defaultCurrency
     */
    public function __construct(string $defaultLocale, private readonly string $defaultCurrency)
    {
        $this->ISOCurrencies         = new ISOCurrencies();
        $this->decimalMoneyFormatter = new DecimalMoneyFormatter($this->ISOCurrencies);
        $this->intlMoneyFormatter    = new IntlMoneyFormatter(
            new NumberFormatter($defaultLocale, NumberFormatter::DECIMAL),
            $this->ISOCurrencies
        );
    }

    public function money2price(Money $money): PriceDTO
    {
        $amount = (string) ceil($this->money2amount($money));
        $money  = $this->price2money(new PriceDTO($amount, $money->getCurrency()->getCode()));

        $priceAmount = $this->intlMoneyFormatter->format($money);

        return new PriceDTO($priceAmount, $money->getCurrency()->getCode());
    }

    /**
     * @throws CurrencyNotFoundException
     */
    public function price2money(PriceDTO $price): Money
    {
        $parser = new DecimalMoneyParser($this->ISOCurrencies);

        try {
            $code  = $price->currency ?: $this->defaultCurrency;
            $money = $parser->parse(
                preg_replace(
                    '/[^\d.-]+/',
                    '',
                    str_replace(',', '.', $price->amount)
                ),
                new Currency($code)
            );
        } catch (UnknownCurrencyException) {
            throw new CurrencyNotFoundException($price->currency);
        }

        return $money;
    }

    public function money2amount(Money $money): float
    {
        return (float) $this->decimalMoneyFormatter->format($money);
    }
}
