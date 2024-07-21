<?php

namespace App;

use App\Dto\Collections\TransactionCollection;
use App\Dto\TransactionDto;
use App\Interfaces\BinListProviderInterface;
use App\Interfaces\CurrencyProviderInterface;
use App\Utils\Country;

class CommissionService
{
  private const EUR_CURRENCY_CODE = 'EUR';

  private const EUR_CURRENCY_RATE = 1;

  public function __construct(
    private BinListProviderInterface $binListProvider,
    private CurrencyProviderInterface $currencyProvider
  ) {}

  /**
   * @throws \Exception
   */
  public function calculate(TransactionCollection $transactions): array
  {
    $commissions = [];
    $transactionCollection = $transactions->getTransactions();

    /** @var TransactionDto[] $transactionCollection */
    foreach ($transactionCollection as $transaction) {
      $rate = $this->getRate($transaction->getCurrency());
      $amountInEur = $this->getAmountInEur($transaction->getAmount(), $rate);
      $countryCode = $this->binListProvider->getCountryCode($transaction->getBin());
      $rateFactor = Country::rateFactor($countryCode);

      $commission = $amountInEur * $rateFactor;
      $commissions[] = number_format(
        ceil($commission * 100) / 100,
        2,
        '.',
        '');
    }

    return $commissions;
  }

  private function getRate(string $currency): float
  {
    return $currency == self::EUR_CURRENCY_CODE
      ? self::EUR_CURRENCY_RATE
      : $this->currencyProvider->getExchangeRate($currency);
  }

  /**
   * @throws \Exception
   */
  private function getAmountInEur(float $amount, float $rate): float
  {
    if($rate <= 0) {
      throw new \Exception('Wrong rate value');
    }

    return $amount / $rate;
  }
}
