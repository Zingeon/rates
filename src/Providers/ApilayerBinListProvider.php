<?php

namespace App\Providers;

use App\Config;
use App\Interfaces\BinListProviderInterface;
use App\Utils\Country;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;

class ApilayerBinListProvider implements BinListProviderInterface
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

    if(!isset($binData['country'])) {
      throw new \Exception('No country in the response');
    }

    return Country::EU_COUNTRY_NAME_TO_COUNTRY_CODE_MAP[$binData['country']] ?? '';
  }

  public function getBinData(string $bin): array
  {
    $response = $this->client->request(
      'GET',
      sprintf('%s/%s', Config::get('APILAYER_API_URL'), $bin),
      [
        'headers' => [
          'apikey' => Config::get('APILAYER_API_KEY')
        ]
      ]
    );
    if ($response->getStatusCode() !== self::HTTP_OK) {
      throw new \Exception('Error retrieving bin data');
    }

    return json_decode($response->getBody(), true);
  }
}
