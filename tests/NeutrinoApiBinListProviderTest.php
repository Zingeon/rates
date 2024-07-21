<?php

use PHPUnit\Framework\TestCase;
use App\Providers\NeutrinoApiBinListProvider;
use GuzzleHttp\Client;
use GuzzleHttp\Psr7\Response;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;

class NeutrinoApiBinListProviderTest extends TestCase
{
  public function testGetCountryCode()
  {
    $mock = new MockHandler([
      new Response(200, [], json_encode([
        "country" => "DENMARK",
        "country-code" => "DK",
        "card-brand" => "VISA",
        "ip-city" => "",
        "ip-blocklists" => [],
        "ip-country-code3" => "",
        "is-commercial" => false,
        "ip-country" => "",
        "bin-number" => "45717360",
        "issuer" => "JYSKE BANK A/S",
        "issuer-website" => "",
        "ip-region" => "",
        "valid" => true,
        "card-type" => "DEBIT",
        "is-prepaid" => false,
        "ip-blocklisted" => false,
        "card-category" => "CLASSIC",
        "issuer-phone" => "",
        "currency-code" => "DKK",
        "ip-matches-bin" => false,
        "country-code3" => "DNK",
        "ip-country-code" => ""
      ]))
    ]);
    $handlerStack = HandlerStack::create($mock);
    $client = new Client(['handler' => $handlerStack]);

    $provider = new NeutrinoApiBinListProvider($client);
    $countryCode = $provider->getCountryCode('45717360');

    $this->assertEquals('DK', $countryCode);
  }
}
