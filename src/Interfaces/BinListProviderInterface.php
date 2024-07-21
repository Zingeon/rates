<?php

namespace App\Interfaces;

interface BinListProviderInterface
{
  public function getCountryCode(string $bin): string;
}
