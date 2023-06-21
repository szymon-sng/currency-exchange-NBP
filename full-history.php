<?php
require_once 'classes/NBPApi.php';
require_once 'classes/ExchangeRateRepository.php';
require_once 'classes/ExchangeRateTable.php';
require_once 'classes/ConversionHistory.php';
require_once 'config/config.php';

?>
<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pełna tabela kursów walut</title>
    <link rel="stylesheet" href="assets/style.css">
</head>
<body>
<ul>
<li><a class="navbar" href="index.php">Strona Główna</a></li>
  <li><a class="navbar" href="full-table.php">Pobierz kursy</a></li>
  <li><a class="navbar" href="full-history.php">Historia</a></li>
  <li><a class="navbar" href="https://github.com/szymon-sng">Github</a></li>
</ul>

<div class="table-history">
    <h2>Historia Przewalutowań</h2>
    <?php
    $conversionHistory = new ConversionHistory($connection);
    $history = $conversionHistory->getConversionHistory();
    foreach ($history as $row) {
        echo 'Waluta źródłowa: ' . $row['source_currency'] . '<br>';
        echo 'Waluta docelowa: ' . $row['target_currency'] . '<br>';
        echo 'Kwota: ' . $row['amount'] . '<br>';
        echo 'Przewalutowana kwota: ' . $row['converted_amount'] . '<br>';
        echo 'Data przewalutowania: ' . $row['conversion_date'] . '<br><br>';
    }
    ?>
</div>
</body>
</html>