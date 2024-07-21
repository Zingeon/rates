<?php

namespace App\Providers;

use App\Config;
use App\Interfaces\CurrencyProviderInterface;
use Exception;
use FreeCurrencyApi\FreeCurrencyApi\FreeCurrencyApiClient;
use FreeCurrencyApi\FreeCurrencyApi\FreeCurrencyApiException;

class FreeCurrencyProvider implements CurrencyProviderInterface
{
  public function __construct(private FreeCurrencyApiClient $client)
  {}

  /**
   * @throws FreeCurrencyApiException
   * @throws Exception
   */
  public function getExchangeRate(string $currency): float
  {
    $rates = $this->client->latest([
      'base_currency' => Config::get('BASE_CURRENCY'),
      'currencies' => $currency
    ]);

    return $rates['data'][$currency] ?? 0;
  }
}
