<?php
class ExchangeRateRepository
{
    private $connection;

    public function __construct(PDO $connection)
    {
        $this->connection = $connection;
    }

    public function getExchangeRates($limit = null): array
    {
        $query = 'SELECT currency_code, rate, date FROM exchange_rates ORDER BY date DESC';

        if ($limit !== null) {
            $query .= ' LIMIT ' . $limit;
        }

        $stmt = $this->connection->query($query);
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

        return $result;
    }

    public function deleteAllExchangeRates(): void
    {
        $this->connection->exec('DELETE FROM exchange_rates');
    }

    public function saveExchangeRate($currencyCode, $rateValue): void
    {
        $date = date('Y-m-d');
        $stmt = $this->connection->prepare('INSERT INTO exchange_rates (currency_code, rate, date) VALUES (:currency_code, :rate, :date)');

        $stmt->execute([
            ':currency_code' => $currencyCode,
            ':rate' => $rateValue,
            ':date' => $date
        ]);
    }
}

?>
