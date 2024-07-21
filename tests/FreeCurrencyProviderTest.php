<?php

use PHPUnit\Framework\TestCase;
use App\Providers\FreeCurrencyProvider;
use FreeCurrencyApi\FreeCurrencyApi\FreeCurrencyApiClient;
use FreeCurrencyApi\FreeCurrencyApi\FreeCurrencyApiException;

class FreeCurrencyProviderTest extends TestCase
{
  private FreeCurrencyApiClient $client;
  private FreeCurrencyProvider $provider;

  protected function setUp(): void
  {
    $this->client = $this->createMock(FreeCurrencyApiClient::class);
    $this->provider = new FreeCurrencyProvider($this->client, 'EUR');
  }

  public function testGetExchangeRate()
  {
    $currency = 'USD';
    $rate = 1.2;

    $this->client->expects($this->once())
      ->method('latest')
      ->with([
        'base_currency' => 'EUR',
        'currencies' => $currency
      ])
      ->willReturn([
        'data' => [
          $currency => $rate
        ]
      ]);

    $result = $this->provider->getExchangeRate($currency);

    $this->assertEquals($rate, $result);
  }

  public function testGetExchangeRateWithInvalidCurrency()
  {
    $currency = 'INVALID';

    $this->client->expects($this->once())
      ->method('latest')
      ->with([
        'base_currency' => 'EUR',
        'currencies' => $currency
      ])
      ->willReturn([
        'data' => []
      ]);

    $result = $this->provider->getExchangeRate($currency);

    $this->assertEquals(0, $result);
  }

  public function testGetExchangeRateWithApiException()
  {
    $currency = 'USD';

    $this->client->expects($this->once())
      ->method('latest')
      ->with([
        'base_currency' => 'EUR',
        'currencies' => $currency
      ])
      ->willThrowException(new FreeCurrencyApiException('API error'));

    $this->expectException(FreeCurrencyApiException::class);
    $this->expectExceptionMessage('API error');

    $this->provider->getExchangeRate($currency);
  }

  public function testGetExchangeRateWithGenericException()
  {
    $currency = 'USD';

    $this->client->expects($this->once())
      ->method('latest')
      ->with([
        'base_currency' => 'EUR',
        'currencies' => $currency
      ])
      ->willThrowException(new Exception('Generic error'));

    $this->expectException(Exception::class);
    $this->expectExceptionMessage('Generic error');

    $this->provider->getExchangeRate($currency);
  }
}
