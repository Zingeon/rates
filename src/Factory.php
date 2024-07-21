<?php

namespace App;

use App\Dto\Collections\TransactionCollection;
use App\Dto\TransactionDto;
use App\Providers\ApilayerBinListProvider;
use App\Providers\FreeCurrencyProvider;
use App\Providers\NeutrinoApiBinListProvider;
use App\Validators\TransactionRowValidator;
use FreeCurrencyApi\FreeCurrencyApi\FreeCurrencyApiClient;
use GuzzleHttp\Client;

class Factory
{
  private static ?Factory $instance = null;

  final private function __construct()
  {}

  final protected function __clone()
  {}

  /**
   * @throws \Exception
   */
  public function __wakeup()
  {
    throw new \Exception("Cannot unserialize a singleton.");
  }

  public static function getInstance(): ?Factory
  {
    if (self::$instance == null) {
      self::$instance = new Factory();
    }

    return self::$instance;
  }

  public function getHttpClient(): Client
  {
    return new Client();
  }

  public function getFreeCurrencyProvider(FreeCurrencyApiClient $freeCurrencyApiClient): FreeCurrencyProvider
  {
    return new FreeCurrencyProvider($freeCurrencyApiClient);
  }

  public function getApilayerBinListProvider(): ApilayerBinListProvider
  {
    return new ApilayerBinListProvider($this->getHttpClient());
  }

  public function getNeutrinoApiBinListProvider(): NeutrinoApiBinListProvider
  {
    return new NeutrinoApiBinListProvider($this->getHttpClient());
  }

  /**
   * @throws \Exception
   */
  public function getFreeCurrencyApiClient(): FreeCurrencyApiClient
  {
    return new FreeCurrencyApiClient(Config::get('FREE_CURRENCY_PROVIDER_API_KEY'));
  }

  /**
   * @throws \Exception
   */
  public function getCommissionService(): CommissionService
  {
    return new CommissionService(
      $this->getApilayerBinListProvider(),
      $this->getFreeCurrencyProvider(
        $this->getFreeCurrencyApiClient()
      )
    );
  }

  public function getTransactionCollection(): TransactionCollection
  {
    return new TransactionCollection();
  }

  public function getTransactionRowValidator(): TransactionRowValidator
  {
    return new TransactionRowValidator();
  }

  public function getTransactionDto(string $bin, float $amount, string $currency): TransactionDto
  {
    return new TransactionDto($bin, $amount, $currency);
  }
}
