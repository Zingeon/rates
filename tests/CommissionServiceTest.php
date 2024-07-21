<?php

use PHPUnit\Framework\TestCase;
use App\CommissionService;
use App\Dto\TransactionDto;
use App\Dto\Collections\TransactionCollection;
use App\Interfaces\BinListProviderInterface;
use App\Interfaces\CurrencyProviderInterface;

class CommissionServiceTest extends TestCase
{
  public function testCalculate()
  {
    $binListProvider = $this->createMock(BinListProviderInterface::class);
    $binListProvider->method('getCountryCode')->willReturn('DE');

    $currencyProvider = $this->createMock(CurrencyProviderInterface::class);
    $currencyProvider->method('getExchangeRate')->willReturn(1.2);

    $calculator = new CommissionService($binListProvider, $currencyProvider);

    $transactionsCollection = new TransactionCollection();
    $transactionsCollection->addTransaction(new TransactionDto('45717360', 100.00, 'USD'));
    $transactionsCollection->addTransaction(new TransactionDto('516793', 50.00, 'EUR'));

    $commissions = $calculator->calculate($transactionsCollection);

    $this->assertEquals(['0.84', '0.50'], $commissions);
  }
}
