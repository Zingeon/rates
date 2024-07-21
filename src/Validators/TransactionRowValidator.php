<?php

namespace App\Validators;

class TransactionRowValidator
{
  public function isValid(array $data): bool
  {
    if (!isset($data['bin']) || !is_string($data['bin'])) {
      return false;
    }

    if (!isset($data['amount']) || !is_numeric($data['amount']) || $data['amount'] < 0) {
      return false;
    }

    if (!isset($data['currency']) || !is_string($data['currency'])) {
      return false;
    }

    return true;
  }
}
