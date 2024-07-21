# Commission Calculator

## Overview
This project calculates commissions for transactions based on BIN data and exchange rates. The commission rates vary based on whether the transaction's country is part of the European Union (EU) or not.

## Installation
1. Clone the repository:
   ```sh
   git clone https://github.com/Zingeon/rates.git
   ```

2. Navigate to the project directory:
   ```sh
   cd rates
   ```

3. Install dependencies:
   ```sh
   composer install
   ```

4. Copy the .env.example file to .env and fill in required data

## Running
   ```sh
   php app.php input.txt
```

## Testing
   ```sh
   vendor/bin/phpunit
```
