<?php

use PHPUnit\Framework\TestCase;
use App\Utils\Country;

class CountryTest extends TestCase
{
  public function testRateFactorForEuCountry()
  {
    $countryCode = 'DE';

    $rate = Country::rateFactor($countryCode);

    $this->assertEquals(0.01, $rate);
  }

  public function testRateFactorForNonEuCountry()
  {
    $countryCode = 'US';

    $rate = Country::rateFactor($countryCode);

    $this->assertEquals(0.02, $rate);
  }

  public function testEuCountryNameToCountryCodeMap()
  {
    $countryName = 'Germany';
    $expectedCountryCode = 'DE';

    $countryCode = Country::EU_COUNTRY_NAME_TO_COUNTRY_CODE_MAP[$countryName] ?? null;

    $this->assertEquals($expectedCountryCode, $countryCode);
  }

  public function testInvalidCountryNameInMap()
  {
    $countryName = 'InvalidCountryName';

    $countryCode = Country::EU_COUNTRY_NAME_TO_COUNTRY_CODE_MAP[$countryName] ?? null;

    $this->assertNull($countryCode);
  }
}
