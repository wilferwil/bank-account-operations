# Bank Account Operations API

![lumen](https://img.shields.io/badge/lumen-8.0-orange)
[![codecov](https://codecov.io/gh/wilferwil/bank-account-operations/branch/master/graph/badge.svg)](https://codecov.io/gh/wilferwil/bank-account-operations)

Project which I developed to demonstrate my work with APIs. I built it on Laravel Lumen 8, a PHP micro-framework. The application simulates financial transactions between bank accounts. Full documentation and usage is at the bottom of this page.

## How to serve and use the API

Within the project folder, execute the following shell command:

<pre><code>composer install</code></pre>

After it, copy the file named **.env.example**, in the project root, to a new file named **.env** on the same folder.

<pre><code>cp .env.example .env</code></pre>

We have to run the migrations to create the necessary database for this project, so run these two commands:

<pre><code>touch database/database.sqlite
php artisan migrate</code></pre>

Be sure that you have PHP 7.3 or higher (Lumen 8 compatible), then run the command below from the project root:

<pre><code>php -S localhost:8000 -t public</code></pre>

That's it. Now you can start using the API from "http://localhost:8000" URI.
Look at the bottom of the page for complete endpoint usage.

## GitHub Actions pipeline and PHPUnit automated tests coverage

On new pull requests to the master branch, all PHPUnit tests will be run by the GitHub Actions pipeline.

In addition, a new coverage report will be available on Codecov.io. Below you can see the latest report.

**Latest coverage report:**

[Click here to view the full coverage report on Codecov.](https://app.codecov.io/gh/wilferwil/bank-account-operations)

![Latest coverage report](.coverage-overview/screencapture-app-codecov-io-gh-wilferwil-bank-account-operations-2022-04-08-04_03_14.png "PHPUnit coverage report on Codecov")

If you want to run the automated tests locally. Just type the following command after the full composer installation:

<pre><code>vendor/bin/phpunit</code></pre>

**Ipkiss/pragmazero test suite on endpoints:**

![ipkiss/pragmazero test suite](.coverage-overview/screencapture-ipkiss-pragmazero-test-2022-04-08-03_55_51.png "ipkiss/pragmazero test suite")

# API usage documentation

### Endpoint: Get Balance
#### Method: GET
>```
>http://localhost:8000/balance?account_id=100
>```

#### Query Params

|Param|value|
|---|---|
|account_id|100|


#### Response: 200
```json
20
```


⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃

### Endpoint: Reset API data
#### Method: POST
>```
>http://localhost:8000/reset
>```

#### Response: 200
```json
OK
```


⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃

### Endpoint: Deposit
#### Method: POST
>```
>http://localhost:8000/event
>```
#### Body (**raw**)

```json
{
    "type": "deposit",
    "destination": "100",
    "amount": 10
}
```

#### Response: 201
```json
{
    "destination": {
        "id": "100",
        "balance": 10
    }
}
```


⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃

### Endpoint: Withdraw
#### Method: POST
>```
>http://localhost:8000/event
>```
#### Body (**raw**)

```json
{
    "type": "withdraw",
    "origin": "100",
    "amount": 5
}
```

#### Response: 201
```json
{
    "origin": {
        "id": "100",
        "balance": 15
    }
}
```


⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃

### Endpoint: Transfer
#### Method: POST
>```
>http://localhost:8000/event
>```
#### Body (**raw**)

```json
{
    "type": "transfer",
    "origin": "100",
    "amount": 15,
    "destination": "300"
}
```

#### Response: 201
```json
{
    "origin": {
        "id": "100",
        "balance": 0
    },
    "destination": {
        "id": "300",
        "balance": 15
    }
}
```


Thanks and enjoy the API! =D

Wilson F. Junior
contact: wilfer.wil@gmail.com
