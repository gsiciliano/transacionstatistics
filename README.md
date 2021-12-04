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
-   [Docker engine](https://docs.docker.com/engine/install/)
-   [Docker compose](https://docs.docker.com/compose/install/)
-   [WSL](https://docs.microsoft.com/en-us/windows/wsl/setup/environment) (if you run under Windows)

## Installation and first run

First of all you must clone this repo: `git clone https://github.com/gsiciliano/transacionstatistics.git `

Then use following instructions to initialize the environment:

-   `cp .env.example .env`
-   `docker-compose -f docker-compose.prod.yml build`
-   `docker-compose -f docker-compose.prod.yml up -d`
-   `docker exec transaction_statistics_app sh init.sh`

## Run application

use `docker-compose -f docker-compose.prod.yml up -d` to start application's containers

## Test application

navigate to <http://localhost:8080> for swagger docs

## How it works
