<?php

use PHPUnit\Framework\TestCase;
use App\Providers\ApilayerBinListProvider;
use GuzzleHttp\Client;
use GuzzleHttp\Psr7\Response;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;

class ApilayerBinListProviderTest extends TestCase
{
  public function testGetCountryCode()
  {
    $mock = new MockHandler([
      new Response(200, [], json_encode([
        "bank_name" => "Pbs International A/s",
        "bin" => "457173",
        "country" => "Denmark",
        "scheme" => "Visa",
        "type" => "Classic",
        "url" => ""
      ]))
    ]);
    $handlerStack = HandlerStack::create($mock);
    $client = new Client(['handler' => $handlerStack]);

    $provider = new ApilayerBinListProvider($client);
    $countryCode = $provider->getCountryCode('45717360');

    $this->assertEquals('DK', $countryCode);
  }
}
