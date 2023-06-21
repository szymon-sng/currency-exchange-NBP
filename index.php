<?php
require_once 'classes/NBPApi.php';
require_once 'classes/ExchangeRateRepository.php';
require_once 'classes/ExchangeRateTable.php';
require_once 'classes/ConversionHistory.php';
require_once 'classes/CurrencyConverter.php';
require_once 'config/config.php';

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
                echo 'Kwota przewalutowana: ' . round($convertedAmount,2);
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