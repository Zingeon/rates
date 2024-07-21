<?php

namespace App\Providers;

use App\Config;
use App\Interfaces\BinListProviderInterface;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;

class NeutrinoApiBinListProvider implements BinListProviderInterface
{
  private const HTTP_OK = 200;

  public function __construct(private Client $client)
  {}

  /**
   * @throws GuzzleException
   * @throws \Exception
   */
  public function getCountryCode(string $bin): string
  {
    $binData = $this->getBinData($bin);
    $countryCode = $binData['country-code'] ?? '';
    if(!$countryCode) {
      throw new \Exception('No country in the response');
    }

    return $countryCode;
  }

  public function getBinData(string $bin): array
  {
    $response = $this->client->request(
      'GET',
      sprintf('%s/bin-lookup', Config::get('NEUTRINO_API_URL')),
      [
        'query' => [
          'bin-number' => $bin
        ],
        'headers' => [
          'User-ID' => Config::get('NEUTRINO_API_USER_ID'),
          'API-Key' => Config::get('NEUTRINO_API_KEY')
        ]
      ]
    );
    if ($response->getStatusCode() !== self::HTTP_OK) {
      throw new \Exception('Error retrieving bin data');
    }

    return json_decode($response->getBody(), true);
  }
}
