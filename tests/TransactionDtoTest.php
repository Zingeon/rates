<?php

use PHPUnit\Framework\TestCase;
use App\Dto\TransactionDto;

class TransactionDtoTest extends TestCase
{
  public function testTransactionDto()
  {
    $transaction = new TransactionDto('45717360', 100.00, 'USD');

    $this->assertEquals('45717360', $transaction->getBin());
    $this->assertEquals(100.00, $transaction->getAmount());
    $this->assertEquals('USD', $transaction->getCurrency());
  }
}
