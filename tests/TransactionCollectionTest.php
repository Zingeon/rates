<?php

use PHPUnit\Framework\TestCase;
use App\Dto\Collections\TransactionCollection;
use App\Dto\TransactionDto;

class TransactionCollectionTest extends TestCase
{
  public function testAddTransaction()
  {
    $transaction = new TransactionDto('45717360', 100.00, 'EUR');
    $collection = new TransactionCollection();

    $collection->addTransaction($transaction);

    $transactions = iterator_to_array($collection->getTransactions());
    $this->assertCount(1, $transactions);
    $this->assertSame($transaction, $transactions[0]);
  }

  public function testGetTransactions()
  {
    $transaction1 = new TransactionDto('45717360', 100.00, 'EUR');
    $transaction2 = new TransactionDto('516793', 50.00, 'USD');
    $collection = new TransactionCollection();

    $collection->addTransaction($transaction1);
    $collection->addTransaction($transaction2);

    $transactions = iterator_to_array($collection->getTransactions());
    $this->assertCount(2, $transactions);
    $this->assertSame($transaction1, $transactions[0]);
    $this->assertSame($transaction2, $transactions[1]);
  }
}
