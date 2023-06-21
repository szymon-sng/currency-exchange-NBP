# Currency Exchange NBP Application

This is a currency exchange application that uses the NBP (Narodowy Bank Polski) API.

## Description

The currency exchange application allows users to convert currencies based on the latest exchange rates obtained from the NBP API. Users can select a source currency, target currency, and enter the amount to be converted. The application retrieves the exchange rates from the database and performs the currency conversion. It also keeps track of the conversion history.

## Files

- `index.php`: Contains the main functionality of the currency converter application.
- `full-table.php`: Displays the full table of exchange rates and allows users to refresh the rates.
- `full-history.php`: Displays the full conversion history.
- `config/config.php`: Configuration file containing the database connection details.
- `classes/NBPApi.php`: Class for retrieving exchange rates from the NBP API.
- `classes/ExchangeRateTable.php`: Class for generating the exchange rate table.
- `classes/ExchangeRateRepository.php`: Class for interacting with the exchange rates in the database.
- `classes/ConversionHistory.php`: Class for retrieving the conversion history from the database.

## Usage

1. Access the application by running `index.php` in a web server environment.
2. Enter the amount to be converted, select the source currency and target currency from the dropdown menus, and click the "Przewalutuj" button.
3. The converted amount will be displayed below the form.
4. You can view the full table of exchange rates by clicking the "Pobierz kursy" link in the navigation menu.
5. You can view the full conversion history by clicking the "Historia" link in the navigation menu.

## Dependencies

- PHP
- PDO extension
- MySQL database

## Requirements

Before starting to work with the project, make sure you have the following tools:

- Apache server or any HTTP server (e.g. XAMPP)
- PHP 7.x 
- MySQL database

## Installation

1. Clone the repository to your local machine:
```
git clone https://github.com/szymon-sng/currency-exchange-NBP.git
```
2. Configure the database connection:
- In `config.php` file you will find the variables that need to be adjusted.

3. Start the HTTP server and open the website in your browser:
```
localhost/your-location
```

