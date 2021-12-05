.PHONY: *

include .env

ifeq ($(OS),Windows_NT)
    COMPOSE_COMMAND := docker-compose
    SYSTEM := Windows
else
	UNAME_S := $(shell uname -s)

	ifeq ($(UNAME_S),Linux)
		COMPOSE_COMMAND := ./run-compose
		SYSTEM := Linux
	else ifeq ($(UNAME_S),Darwin)
    	COMPOSE_COMMAND := ./run-compose
    	SYSTEM := Mac
	endif

endif
export OS=$(SYSTEM)

help:                                   ## Show this help.
	@fgrep -h "##" $(MAKEFILE_LIST) | fgrep -v fgrep | sed -e 's/\\$$//' | sed -e 's/##//'
up:                                     ## Turn on container services
	$(COMPOSE_COMMAND) --file docker-compose.$(APP_ENV).yml up -d
stop:                                   ## Turn off container services
	$(COMPOSE_COMMAND) --file docker-compose.$(APP_ENV).yml stop
down:                                   ## Turn on and remove container services
	$(COMPOSE_COMMAND) --file docker-compose.$(APP_ENV).yml down
build:                                  ## Build container images
	$(COMPOSE_COMMAND) --file docker-compose.$(APP_ENV).yml build
rebuild:                                ## Rebuild and turn on container services
	$(COMPOSE_COMMAND) --file docker-compose.$(APP_ENV).yml up -d --build
shell:                                  ## Enter application container shell
	docker exec -it transaction_statistics_app bash
init:                                  ## Run init script for first run
	docker exec -it transaction_statistics_app sh init.sh
test:                                  ## Run tests suite when in local environment
	docker exec -it transaction_statistics_app composer test


.DEFAULT_GOAL := help
