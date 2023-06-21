<?php
class NBPApi
{
    private $apiUrl = 'https://api.nbp.pl/api/exchangerates/tables/A/';

    public function getExchangeRates(): array
    {
        $url = $this->apiUrl;
        $response = file_get_contents($url);
        $data = json_decode($response, true);

        return $data[0]['rates'];
    }
}

?>
