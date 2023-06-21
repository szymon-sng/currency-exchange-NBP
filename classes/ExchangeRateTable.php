<?php
class ExchangeRateTable
{
    private $connection;

    public function __construct(PDO $connection)
    {
        $this->connection = $connection;
    }

    public function generateTable($limit = null): string
    {
        $exchangeRateRepository = new ExchangeRateRepository($this->connection);
        $exchangeRates = $exchangeRateRepository->getExchangeRates($limit);

        $table = '<table><thead><tr><th>Waluta</th><th>Kurs</th><th>Data</th></tr></thead><tbody>';

        foreach ($exchangeRates as $row) {
            $table .= '<tr><td>' . $row['currency_code'] . '</td><td>' . $row['rate'] . '</td><td>' . $row['date'] . '</td></tr>';
        }

        $table .= '</tbody></table>';

        return $table;
    }
}

?>
