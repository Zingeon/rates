<?php

require 'vendor/autoload.php';

use App\Config;
use App\Factory;


$factory = Factory::getInstance();

$commissionService = $factory->getCommissionService();
$transactionCollection = $factory->getTransactionCollection();
$transactionValidator = $factory->getTransactionRowValidator();

$inputFile = $argv[1];
if(!$inputFile) {
  throw new Exception("Please mention a valid txt file.\n");
}
$importFilepath = sprintf('%s/%s', Config::get('IMPORT_DIR'), $inputFile);
if (!file_exists($importFilepath)) {
  throw new Exception("Input file does not exist.\n");
}

$fileHandle = fopen($importFilepath, 'r');
if (!$fileHandle) {
  throw new Exception("Error opening the input file.\n");
}

while (($fileLine = fgets($fileHandle)) !== false) {
  $fileLineData = json_decode($fileLine, true);
  if (!is_array($fileLineData)) {
    continue;
  }

  if (!$transactionValidator->isValid($fileLineData)) {
    continue;
  }

  $transactionDto = $factory->getTransactionDto(
    $fileLineData['bin'],
    $fileLineData['amount'],
    $fileLineData['currency']
  );
  $transactionCollection->addTransaction($transactionDto);
}
fclose($fileHandle);

$commissions = $commissionService->calculate($transactionCollection);
foreach ($commissions as $commission) {
  echo $commission . "\n";
}
