<?php

namespace App\Interfaces;

interface CurrencyProviderInterface
{
  public function getExchangeRate(string $currency): float;
}
