<?php

namespace App;

use Dotenv\Dotenv;
use Exception;

class Config
{
  public static function get(string $key): string
  {
    Dotenv::createImmutable(dirname(__DIR__))->load();
    if(!isset($_ENV[$key])) {
      throw new Exception(
        sprintf("%s key is missing. Check if it exists in your .env file.\n", $key)
      );
    }

    return $_ENV[$key];
  }
}
