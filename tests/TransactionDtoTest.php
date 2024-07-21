<?php

use PHPUnit\Framework\TestCase;
use App\Dto\TransactionDto;

class TransactionDtoTest extends TestCase
{
  public function testGetBin()
  {
    $bin = '45717360';
    $amount = 100.00;
    $currency = 'EUR';

    $transactionDto = new TransactionDto($bin, $amount, $currency);

    $this->assertEquals($bin, $transactionDto->getBin());
  }

  public function testGetAmount()
  {
    $bin = '45717360';
    $amount = 100.00;
    $currency = 'EUR';

    $transactionDto = new TransactionDto($bin, $amount, $currency);

    $this->assertEquals($amount, $transactionDto->getAmount());
  }

  public function testGetCurrency()
  {
    $bin = '45717360';
    $amount = 100.00;
    $currency = 'EUR';

    $transactionDto = new TransactionDto($bin, $amount, $currency);

    $this->assertEquals($currency, $transactionDto->getCurrency());
  }
}
