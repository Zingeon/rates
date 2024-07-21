<?php

use App\Providers\FreeCurrencyProvider;
use FreeCurrencyApi\FreeCurrencyApi\FreeCurrencyApiClient;
use PHPUnit\Framework\TestCase;

class FreeCurrencyProviderTest extends TestCase
{
  public function testGetExchangeRate()
  {
    $mock = $this->createMock(FreeCurrencyApiClient::class);
    $mock->method('latest')->willReturn(['data' => ['USD' => 1.2]]);

    $provider = new FreeCurrencyProvider($mock);
    $rate = $provider->getExchangeRate('USD');

    $this->assertEquals(1.2, $rate);
  }
}
