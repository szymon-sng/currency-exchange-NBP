<?php
class NBPApi
{
    private $apiUrl = 'http://api.nbp.pl/api/exchangerates/tables/A/';

    public function getExchangeRates(): array
{
    $date = '2023-06-20'; // zmieniona data
    $url = $this->apiUrl . $date;
    $response = file_get_contents($url);
    $data = json_decode($response, true);

    return $data[0]['rates'];
}
}

?>
