<?php

use App\Config;
use App\Providers\ApilayerBinListProvider;
use App\Utils\Country;
use PHPUnit\Framework\TestCase;
use GuzzleHttp\Client;
use GuzzleHttp\Psr7\Response;

class ApilayerBinListProviderTest extends TestCase
{
  private Client $client;
  private ApilayerBinListProvider $provider;

  private const BIN_NUMBER = '45717360';

  protected function setUp(): void
  {
    $this->client = $this->createMock(Client::class);
    $this->provider = new ApilayerBinListProvider($this->client);
  }
  public function testGetCountryCodeWithValidBin()
  {
    $responseBody = json_encode(['country' => 'Germany']);
    $response = new Response(200, [], $responseBody);

    $this->client->expects($this->once())
      ->method('request')
      ->with('GET', sprintf('%s/%s', Config::get('APILAYER_API_URL'), self::BIN_NUMBER), [
        'headers' => [
          'apikey' => Config::get('APILAYER_API_KEY')
        ]
      ])
      ->willReturn($response);

    $countryCode = $this->provider->getCountryCode(self::BIN_NUMBER);

    $this->assertEquals(Country::EU_COUNTRY_NAME_TO_COUNTRY_CODE_MAP['Germany'], $countryCode);
  }


  public function testGetCountryCodeWithInvalidResponse()
  {
    $responseBody = json_encode([]);
    $response = new Response(200, [], $responseBody);

    $this->client->expects($this->once())
      ->method('request')
      ->with('GET', sprintf('%s/%s', Config::get('APILAYER_API_URL'), self::BIN_NUMBER), [
        'headers' => [
          'apikey' => Config ::get('APILAYER_API_KEY')
        ]
      ])
      ->willReturn($response);

    $this->expectException(\Exception::class);
    $this->expectExceptionMessage('No country in the response');

    $this->provider->getCountryCode(self::BIN_NUMBER);
  }

  public function testGetCountryCodeWithNonEuCountry()
  {
    $responseBody = json_encode(['country' => 'United States']);
    $response = new Response(200, [], $responseBody);

    $this->client->expects($this->once())
      ->method('request')
      ->with('GET', sprintf('%s/%s', Config::get('APILAYER_API_URL'), self::BIN_NUMBER), [
        'headers' => [
          'apikey' => Config::get('APILAYER_API_KEY')
        ]
      ])
      ->willReturn($response);

    $countryCode = $this->provider->getCountryCode(self::BIN_NUMBER);

    $this->assertEquals('', $countryCode);
  }

  public function testGetBinDataWithErrorResponse()
  {
    $response = new Response(400);

    $this->client->expects($this->once())
      ->method('request')
      ->with('GET', sprintf('%s/%s', Config::get('APILAYER_API_URL'), self::BIN_NUMBER), [
        'headers' => [
          'apikey' => Config::get('APILAYER_API_KEY')
        ]
      ])
      ->willReturn($response);

    $this->expectException(\Exception::class);
    $this->expectExceptionMessage('Error retrieving bin data');

    $this->provider->getBinData(self::BIN_NUMBER);
  }

  public function testGetBinDataWithValidResponse()
  {
    $responseBody = json_encode(['country' => 'Germany']);
    $response = new Response(200, [], $responseBody);

    $this->client->expects($this->once())
      ->method('request')
      ->with('GET', sprintf('%s/%s', Config::get('APILAYER_API_URL'), self::BIN_NUMBER), [
        'headers' => [
          'apikey' => Config::get('APILAYER_API_KEY')
        ]
      ])
      ->willReturn($response);

    $binData = $this->provider->getBinData(self::BIN_NUMBER);

    $this->assertEquals(['country' => 'Germany'], $binData);
  }
}
