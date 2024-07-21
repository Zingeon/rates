<?php

namespace App\Utils;

class Country
{
  private const EU_RATE = 0.01;

  private const NON_EU_RATE = 0.02;

  private const EU_COUNTRY_AT = 'AT';
  private const EU_COUNTRY_BE = 'BE';
  private const EU_COUNTRY_BG = 'BG';
  private const EU_COUNTRY_CY = 'CY';
  private const EU_COUNTRY_CZ = 'CZ';
  private const EU_COUNTRY_DE = 'DE';
  private const EU_COUNTRY_DK = 'DK';
  private const EU_COUNTRY_EE = 'EE';
  private const EU_COUNTRY_ES = 'ES';
  private const EU_COUNTRY_FI = 'FI';
  private const EU_COUNTRY_FR = 'FR';
  private const EU_COUNTRY_GR = 'GR';
  private const EU_COUNTRY_HR = 'HR';
  private const EU_COUNTRY_HU = 'HU';
  private const EU_COUNTRY_IE = 'IE';
  private const EU_COUNTRY_IT = 'IT';
  private const EU_COUNTRY_LT = 'LT';
  private const EU_COUNTRY_LU = 'LU';
  private const EU_COUNTRY_LV = 'LV';
  private const EU_COUNTRY_MT = 'MT';
  private const EU_COUNTRY_NL = 'NL';
  private const EU_COUNTRY_PO = 'PO';
  private const EU_COUNTRY_PT = 'PT';
  private const EU_COUNTRY_RO = 'RO';
  private const EU_COUNTRY_SE = 'SE';
  private const EU_COUNTRY_SI = 'SI';
  private const EU_COUNTRY_SK = 'SK';

  private const EU_COUNTRIES = [
    self::EU_COUNTRY_AT,
    self::EU_COUNTRY_BE,
    self::EU_COUNTRY_BG,
    self::EU_COUNTRY_CY,
    self::EU_COUNTRY_CZ,
    self::EU_COUNTRY_DE,
    self::EU_COUNTRY_DK,
    self::EU_COUNTRY_EE,
    self::EU_COUNTRY_ES,
    self::EU_COUNTRY_FI,
    self::EU_COUNTRY_FR,
    self::EU_COUNTRY_GR,
    self::EU_COUNTRY_HR,
    self::EU_COUNTRY_HU,
    self::EU_COUNTRY_IE,
    self::EU_COUNTRY_IT,
    self::EU_COUNTRY_LT,
    self::EU_COUNTRY_LU,
    self::EU_COUNTRY_LV,
    self::EU_COUNTRY_MT,
    self::EU_COUNTRY_NL,
    self::EU_COUNTRY_PO,
    self::EU_COUNTRY_PT,
    self::EU_COUNTRY_RO,
    self::EU_COUNTRY_SE,
    self::EU_COUNTRY_SI,
    self::EU_COUNTRY_SK
  ];

  public const EU_COUNTRY_NAME_TO_COUNTRY_CODE_MAP = [
    'Austria' => self::EU_COUNTRY_AT,
    'Belgium' => self::EU_COUNTRY_BE,
    'Bulgaria' => self::EU_COUNTRY_BG,
    'Cyprus' => self::EU_COUNTRY_CY,
    'Czech Republic' => self::EU_COUNTRY_CZ,
    'Germany' => self::EU_COUNTRY_DE,
    'Denmark' => self::EU_COUNTRY_DK,
    'Estonia' => self::EU_COUNTRY_EE,
    'Spain' => self::EU_COUNTRY_ES,
    'Finland' => self::EU_COUNTRY_FI,
    'France' => self::EU_COUNTRY_FR,
    'Greece' => self::EU_COUNTRY_GR,
    'Croatia' => self::EU_COUNTRY_HR,
    'Hungary' => self::EU_COUNTRY_HU,
    'Ireland' => self::EU_COUNTRY_IE,
    'Italy' => self::EU_COUNTRY_IT,
    'Lithuania' => self::EU_COUNTRY_LT,
    'Luxembourg' => self::EU_COUNTRY_LU,
    'Latvia' => self::EU_COUNTRY_LV,
    'Malta' => self::EU_COUNTRY_MT,
    'Netherlands' => self::EU_COUNTRY_NL,
    'Poland' => self::EU_COUNTRY_PO,
    'Portugal' => self::EU_COUNTRY_PT,
    'Romania' => self::EU_COUNTRY_RO,
    'Sweden' => self::EU_COUNTRY_SE,
    'Slovenia' => self::EU_COUNTRY_SI,
    'Slovakia' => self::EU_COUNTRY_SK
  ];

  public static function rateFactor(string $countryCode): float
  {
    return in_array($countryCode, self::EU_COUNTRIES) ? self::EU_RATE : self::NON_EU_RATE;
  }
}
