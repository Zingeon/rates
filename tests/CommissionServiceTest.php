<?php

use PHPUnit\Framework\TestCase;
use App\CommissionService;
use App\Dto\Collections\TransactionCollection;
use App\Dto\TransactionDto;
use App\Interfaces\BinListProviderInterface;
use App\Interfaces\CurrencyProviderInterface;

class CommissionServiceTest extends TestCase
{
  private BinListProviderInterface $binListProvider;
  private CurrencyProviderInterface $currencyProvider;
  private CommissionService $commissionService;
  
  private const BIN_NUMBER = '45717360';

  protected function setUp(): void
  {
    $this->binListProvider = $this->createMock(BinListProviderInterface::class);
    $this->currencyProvider = $this->createMock(CurrencyProviderInterface::class);
    $this->commissionService = new CommissionService($this->binListProvider, $this->currencyProvider);
  }

  public function testCalculateWithEuCountry()
  {
    $transaction = new TransactionDto(self::BIN_NUMBER, 100.00, 'USD');
    $transactionCollection = new TransactionCollection();
    $transactionCollection->addTransaction($transaction);

    $this->currencyProvider->expects($this->once())
      ->method('getExchangeRate')
      ->with('USD')
      ->willReturn(1.2);

    $this->binListProvider->expects($this->once())
      ->method('getCountryCode')
      ->with(self::BIN_NUMBER)
      ->willReturn('DE');

    $commissions = $this->commissionService->calculate($transactionCollection);

    $this->assertEquals(['0.84'], $commissions);
  }

  public function testCalculateWithNonEuCountry()
  {
    $transaction = new TransactionDto(self::BIN_NUMBER, 100.00, 'USD');
    $transactionCollection = new TransactionCollection();
    $transactionCollection->addTransaction($transaction);

    $this->currencyProvider->expects($this->once())
      ->method('getExchangeRate')
      ->with('USD')
      ->willReturn(1.2);

    $this->binListProvider->expects($this->once())
      ->method('getCountryCode')
      ->with(self::BIN_NUMBER)
      ->willReturn('US');

    $commissions = $this->commissionService->calculate($transactionCollection);

    $this->assertEquals(['1.67'], $commissions);
  }

  public function testCalculateWithInvalidRate()
  {
    $transaction = new TransactionDto('45717360', 100.00, 'USD');
    $transactionCollection = new TransactionCollection();
    $transactionCollection->addTransaction($transaction);

    $this->currencyProvider->expects($this->once())
      ->method('getExchangeRate')
      ->with('USD')
      ->willReturn(0.0);

    $this->binListProvider->expects($this->never())
      ->method('getCountryCode');

    $this->expectException(\Exception::class);
    $this->expectExceptionMessage('Wrong rate value');

    $this->commissionService->calculate($transactionCollection);
  }

  public function testCalculateWithEurCurrency()
  {
    $transaction = new TransactionDto(self::BIN_NUMBER, 100.00, 'EUR');
    $transactionCollection = new TransactionCollection();
    $transactionCollection->addTransaction($transaction);

    $this->binListProvider->expects($this->once())
      ->method('getCountryCode')
      ->with(self::BIN_NUMBER)
      ->willReturn('DE');

    $commissions = $this->commissionService->calculate($transactionCollection);

    $this->assertEquals(['1.00'], $commissions);
  }
}
