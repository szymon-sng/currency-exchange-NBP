<?php
require_once 'classes/NBPApi.php';
require_once 'classes/ExchangeRateRepository.php';
require_once 'classes/ExchangeRateTable.php';
require_once 'classes/ConversionHistory.php';

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

$dsn = 'mysql:host=localhost;dbname=currency_exchange;charset=utf8';
$username = 'root';
$password = '';

try {
    $connection = new PDO($dsn, $username, $password);
    $connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    exit('Connection failed: ' . $e->getMessage());
}

$currencyConverter = new CurrencyConverter($connection);

$currencies = $currencyConverter->getCurrencies();
?>

<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Currency Converter</title>
    <link rel="stylesheet" href="assets/style.css">
</head>
<body>
<ul>
<li><a class="navbar" href="index.php">Strona Główna</a></li>
  <li><a class="navbar" href="full-table.php">Pobierz kursy</a></li>
  <li><a class="navbar" href="full-history.php">Historia</a></li>
  <li><a class="navbar" href="https://github.com/szymon-sng">Github</a></li>
</ul>
    <div class="flex-container">
    <div class="table-container">
    <h2>Tabela Kursów Walut</h2>
    <?php
        $exchangeRateTable = new ExchangeRateTable($connection);
        echo $exchangeRateTable->generateTable(10);
    ?>
    <a href="full-table.php">
    <input class="button-history" value="Zobacz wszystkie kursy">
    </a>
     </div>
     <div class="container">
    <div class="form-container">
    <h1>Kursy Walut</h1>
    <form action="index.php" method="POST">
        Kwota: <input type="number" name="amount" required><br>
        Waluta Źródłowa:
        <select name="source_currency" required>
            <?php foreach ($currencies as $currency): ?>
                <option value="<?= htmlspecialchars($currency) ?>"><?= htmlspecialchars($currency) ?></option>
            <?php endforeach; ?>
        </select><br>
        Waluta Docelowa:
        <select name="target_currency" required>
            <?php foreach ($currencies as $currency): ?>
                <option value="<?= htmlspecialchars($currency) ?>"><?= htmlspecialchars($currency) ?></option>
            <?php endforeach; ?>
        </select><br>
        <input type="submit" class="button" value="Przewalutuj">
        <br>
        <?php
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $amount = filter_input(INPUT_POST, 'amount', FILTER_VALIDATE_FLOAT);
            $sourceCurrency = filter_input(INPUT_POST, 'source_currency', FILTER_SANITIZE_STRING);
            $targetCurrency = filter_input(INPUT_POST, 'target_currency', FILTER_SANITIZE_STRING);

            if ($amount === false) {
                echo 'Zła kwota.';
            } elseif ($sourceCurrency === null || $sourceCurrency === false) {
                echo 'Zła waluta źródłowa.';
            } elseif ($targetCurrency === null || $targetCurrency === false) {
                echo 'Zła waluta docelowa.';
            } else {
                $convertedAmount = $currencyConverter->convertCurrency($sourceCurrency, $targetCurrency, $amount);
                echo 'Kwota przewalutowana: ' . $convertedAmount;
            }
        }
        ?>
    </form>
    </div>
    </div>
    <div class="table-history">
        <h2>Historia Przewalutowań</h2>
        <?php
        $conversionHistory = new ConversionHistory($connection);
        $history = $conversionHistory->getConversionHistory(3);
        foreach ($history as $row) {
            echo 'Waluta źródłowa: ' . $row['source_currency'] . '<br>';
            echo 'Waluta docelowa: ' . $row['target_currency'] . '<br>';
            echo 'Kwota: ' . $row['amount'] . '<br>';
            echo 'Przewalutowana kwota: ' . $row['converted_amount'] . '<br>';
            echo 'Data przewalutowania: ' . $row['conversion_date'] . '<br><br>';
        }
        ?>
        <a href="full-history.php">
            <input class="button-history" value="Zobacz pełną historie">
        </a>
    </div>
</div>
</body>
</html>