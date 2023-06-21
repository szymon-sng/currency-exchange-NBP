<?php
class ExchangeRateTable
{
    private $connection;

    public function __construct(PDO $connection)
    {
        $this->connection = $connection;
    }

    public function generateTable(): string
    {
        $stmt = $this->connection->query('SELECT currency_code, rate, date FROM exchange_rates ORDER BY date DESC LIMIT 10');
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $table = '<table><thead><tr><th>Waluta</th><th>Kurs</th><th>Data</th></tr></thead><tbody>';

        foreach ($result as $row) {
            $table .= '<tr><td>' . $row['currency_code'] . '</td><td>' . $row['rate'] . '</td><td>' . $row['date'] . '</td></tr>';
        }

        $table .= '</tbody></table>';

        return $table;
    }
}

?>
