# Transaction statistics

## Description

A RESTful API for collecting statistics. The main use case for the API is to calculate realtime statistics for the last 60 seconds of transactions.

| Endpoint                 | Authenticated | Description                                                                          |
| ------------------------ | ------------- | ------------------------------------------------------------------------------------ |
| **POST** /transactions   | yes           | called every time a transaction is made. It is also the sole input of this rest API. |
| **GET** /transactions    | yes           | returns all the transaction from a specified timestamp                               |
| **DELETE** /transactions | yes           | deletes all transactions                                                             |
| **GET** /statistics      | no            | returns the statistic based of the transactions of the last 60 seconds.              |

## Requirements

In order to run this application you must install at least:

-   an internet connection in order to download docker images
-   [Git](https://git-scm.com/) installed on your machine 
-   [Docker engine](https://docs.docker.com/engine/install/)
-   [Docker compose](https://docs.docker.com/compose/install/)
-   [WSL](https://docs.microsoft.com/en-us/windows/wsl/setup/environment) (if you run under Windows)

## Installation, setup and first run on a local environment

First of all you must clone this repo: `git clone https://github.com/gsiciliano/transacionstatistics.git `

### Use following instructions to initialize your local environment

-   `cd transactionstatistics`
-   `cp .env.example .env` (optionally you can make change to .env file if needed)
  
_Note that if you've make utility installed can jump to **Make Utility** section below_

- build:  `docker-compose -f docker-compose.production.yml build`
- up:     `docker-compose -f docker-compose.production.yml up -d`
- init:   `docker exec -it transaction_statistics_app sh init.sh`

### Run application's containers 

use `docker-compose -f docker-compose.production.yml up -d` to start application's containers

### Make utility

You can use make commands to replace initialization command:

- `make build`
- `make up`
- `make init`

### Run test suite (only for local environment)

in local environment configuration you can run test suite using following command:

- for run tests with explictit descriptions:
  - `docker exec transaction_statistics_app php artisan test`

- for run tests with summary:
  - `docker exec transaction_statistics_app composer test`

### Production ready

To deploy transactions statistics api in production you must change from `APP_ENV=local` to `APP_ENV=production` in your `.env` file before run `init.sh` script

## Test application

### on local environment

navigate to <http://localhost:8080> for swagger docs

you can use following credentials for test api via swaggerdocs or postman collection

`client_id: 1`

`client_secret: 0Vw967ioyYp2zozSZS3cOaivSTycOJW0SNo9KfHP`

### with [Postman](https://www.postman.com/) 

you can import this [postman collection](postman/Transaction%20Statistics.postman_collection.json) to try transactions statistics API 

## How it works

The Transactions statistic API is composed by following containers:

- Ngingx web server container
- Php application container
- Redis service container
- Scheduler container
- MariaDB database container

When a new transaction is posted (with `/transactions` `POST`), if timestamp is in a time window (by default is 60 seconds), is putted in a redis based queue that handle time window statistics.

You can monitor how many transactions are queued by navigating `/statistics` `GET` route.

every time a transaction felt down from the time window, the system put into a persistent database storage and remove it from redis queue.

With `/transactions` `GET` route you can query persistent transactions starting from a given timestamp.

You may delete all transaction both from memory and persistence with the `/transactions` `DELETE` route.