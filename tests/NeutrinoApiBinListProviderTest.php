<?php

use PHPUnit\Framework\TestCase;
use App\Providers\NeutrinoApiBinListProvider;
use GuzzleHttp\Client;
use GuzzleHttp\Psr7\Response;
use Dotenv\Dotenv;

class NeutrinoApiBinListProviderTest extends TestCase
{
  private const BIN_NUMBER = '45717360';

  private Client $client;
  private NeutrinoApiBinListProvider $provider;

  protected function setUp(): void
  {
    if (file_exists(__DIR__ . '/../.env')) {
      $dotenv = Dotenv::createImmutable(__DIR__ . '/../');
      $dotenv->load();
    }

    $this->client = $this->createMock(Client::class);
    $this->provider = new NeutrinoApiBinListProvider($this->client);

    $this->mockConfig();
  }

  private function mockConfig()
  {
    $configClass = new class {
      public static function get($key)
      {
        return $_ENV[$key] ?? null;
      }
    };

    $reflectionClass = new ReflectionClass('App\Config');
    $reflectionMethod = $reflectionClass->getMethod('get');
    $reflectionMethod->setAccessible(true);
    $reflectionMethod->invoke($configClass, 'NEUTRINO_API_URL');
  }

  public function testGetCountryCode()
  {
    $binData = ['country-code' => 'DE'];
    $response = new Response(200, [], json_encode($binData));

    $this->client->expects($this->once())
      ->method('request')
      ->with('GET', 'https://neutrinoapi.net/bin-lookup', [
        'query' => ['bin-number' => self::BIN_NUMBER],
        'headers' => [
          'User-ID' => $_ENV['NEUTRINO_API_USER_ID'],
          'API-Key' => $_ENV['NEUTRINO_API_KEY']
        ]
      ])
      ->willReturn($response);

    $countryCode = $this->provider->getCountryCode(self::BIN_NUMBER);

    $this->assertEquals('DE', $countryCode);
  }

  public function testGetCountryCodeWithNoCountryCode()
  {
    $binData = [];
    $response = new Response(200, [], json_encode($binData));

    $this->client->expects($this->once())
      ->method('request')
      ->with('GET', 'https://neutrinoapi.net/bin-lookup', [
        'query' => ['bin-number' => self::BIN_NUMBER],
        'headers' => [
          'User-ID' => $_ENV['NEUTRINO_API_USER_ID'],
          'API-Key' => $_ENV['NEUTRINO_API_KEY']
        ]
      ])
      ->willReturn($response);

    $this->expectException(\Exception::class);
    $this->expectExceptionMessage('No country in the response');

    $this->provider->getCountryCode(self::BIN_NUMBER);
  }

  public function testGetCountryCodeWithErrorResponse()
  {
    $response = new Response(400);

    $this->client->expects($this->once())
      ->method('request')
      ->with('GET', 'https://neutrinoapi.net/bin-lookup', [
        'query' => ['bin-number' => self::BIN_NUMBER],
        'headers' => [
          'User-ID' => $_ENV['NEUTRINO_API_USER_ID'],
          'API-Key' => $_ENV['NEUTRINO_API_KEY']
        ]
      ])
      ->willReturn($response);

    $this->expectException(\Exception::class);
    $this->expectExceptionMessage('Error retrieving bin data');

    $this->provider->getCountryCode(self::BIN_NUMBER);
  }

  public function testGetBinData()
  {
    $binData = ['country-code' => 'DE'];
    $response = new Response(200, [], json_encode($binData));

    $this->client->expects($this->once())
      ->method('request')
      ->with('GET', 'https://neutrinoapi.net/bin-lookup', [
        'query' => ['bin-number' => self::BIN_NUMBER],
        'headers' => [
          'User-ID' => $_ENV['NEUTRINO_API_USER_ID'],
          'API-Key' => $_ENV['NEUTRINO_API_KEY']
        ]
      ])
      ->willReturn($response);

    $result = $this->provider->getBinData(self::BIN_NUMBER);

    $this->assertEquals($binData, $result);
  }

  public function testGetBinDataWithErrorResponse()
  {
    $response = new Response(400);

    $this->client->expects($this->once())
      ->method('request')
      ->with('GET', 'https://neutrinoapi.net/bin-lookup', [
        'query' => ['bin-number' => self::BIN_NUMBER],
        'headers' => [
          'User-ID' => $_ENV['NEUTRINO_API_USER_ID'],
          'API-Key' => $_ENV['NEUTRINO_API_KEY']
        ]
      ])
      ->willReturn($response);

    $this->expectException(\Exception::class);
    $this->expectExceptionMessage('Error retrieving bin data');

    $this->provider->getBinData(self::BIN_NUMBER);
  }
}
