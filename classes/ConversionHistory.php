<?php
class ConversionHistory
{
    private $connection;

    public function __construct(PDO $connection)
    {
        $this->connection = $connection;
    }

    public function getConversionHistory($limit = null): array
    {
        $query = 'SELECT source_currency, target_currency, amount, converted_amount, conversion_date FROM currency_conversions ORDER BY conversion_date DESC';

        if ($limit !== null) {
            $query .= ' LIMIT ' . $limit;
        }

        $stmt = $this->connection->query($query);
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

        return $result;
    }
}
?>