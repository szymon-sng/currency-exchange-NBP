<?php
class CurrencyConverter
{
    private $connection;

    public function __construct(PDO $connection)
    {
        $this->connection = $connection;
    }

    public function convertCurrency(string $sourceCurrency, string $targetCurrency, float $amount): float
    {
        $sourceRate = $this->getExchangeRate($sourceCurrency);
        $targetRate = $this->getExchangeRate($targetCurrency);

        if ($sourceRate && $targetRate) {
            $convertedAmount = $amount * ($sourceRate / $targetRate);
            $this->saveConversion($sourceCurrency, $targetCurrency, $amount, $convertedAmount);
            return $convertedAmount;
        }

        return 0;
    }

    private function getExchangeRate(string $currencyCode): float
    {
        $stmt = $this->connection->prepare('SELECT rate FROM exchange_rates WHERE currency_code = :currency_code ORDER BY date DESC LIMIT 1');
        $stmt->execute([':currency_code' => $currencyCode]);
        $rate = $stmt->fetchColumn();
        $stmt->closeCursor();

        return $rate ? (float) $rate : 0;
    }

    private function saveConversion(string $sourceCurrency, string $targetCurrency, float $amount, float $convertedAmount): void
    {
        $stmt = $this->connection->prepare('INSERT INTO currency_conversions (source_currency, target_currency, amount, converted_amount, conversion_date) VALUES (:source_currency, :target_currency, :amount, :converted_amount, NOW())');
        $stmt->execute([
            ':source_currency' => $sourceCurrency,
            ':target_currency' => $targetCurrency,
            ':amount' => $amount,
            ':converted_amount' => $convertedAmount
        ]);
    }

    public function getCurrencies(): array
    {
        $stmt = $this->connection->query('SELECT DISTINCT currency_code FROM exchange_rates');
        $currencies = $stmt->fetchAll(PDO::FETCH_COLUMN);
        $stmt->closeCursor();

        return $currencies;
    }
}
?>