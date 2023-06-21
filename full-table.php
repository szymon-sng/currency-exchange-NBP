<?php
require_once 'classes/ExchangeRateTable.php';
require_once 'classes/NBPApi.php';
require_once 'classes/ExchangeRateRepository.php';

$dsn = 'mysql:host=localhost;dbname=currency_exchange;charset=utf8';
$username = 'root';
$password = '';

try {
    $connection = new PDO($dsn, $username, $password);
    $connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    exit('Connection failed: ' . $e->getMessage());
}

$exchangeRateTable = new ExchangeRateTable($connection);
$table = $exchangeRateTable->generateTable();

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['refresh_rates'])) {
    $nbpApi = new NBPApi();
    $exchangeRates = $nbpApi->getExchangeRates();
}
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
    <br>
    <form method="post">
        <input type="submit" class="button" name="refresh_rates" value="Odśwież kursy">
    </form>
    <div class="full-table">
        <h2>Pełna tabela kursów walut</h2>
        <?php echo $table; ?>
    </div>
</body>
</html>