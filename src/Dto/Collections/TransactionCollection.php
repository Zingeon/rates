<?php

namespace App\Dto\Collections;

use App\Dto\TransactionDto;

class TransactionCollection
{
  private array $transactions = [];

  public function addTransaction(TransactionDto $transaction): void
  {
    $this->transactions[] = $transaction;
  }

  public function getTransactions(): \Generator
  {
    foreach ($this->transactions as $transaction) {
      yield $transaction;
    }
  }
}
