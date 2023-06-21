<?php
class ExchangeRateRepository
{
    private $connection;

    public function __construct(PDO $connection)
    {
        $this->connection = $connection;
    }

    public function saveExchangeRates(array $exchangeRates): void
    {
        $date = date('Y-m-d');
        $stmt = $this->connection->prepare('INSERT INTO exchange_rates (currency_code, rate, date) VALUES (:currency_code, :rate, :date)');

        foreach ($exchangeRates as $rate) {
            $stmt->execute([
                ':currency_code' => $rate['code'],
                ':rate' => $rate['mid'],
                ':date' => $date
            ]);
        }
    }
}

?>
