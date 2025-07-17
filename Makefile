# INCLUDES #
include .env

# COLOURS #

GREEN = \033[0:32m
COLOR_OFF = \033[0m

# RULES #

all: build

volumes:
	@echo "$(GREEN)<+> CREATING VOLUMES <+> $(COLOR_OFF)"
	@mkdir -p $(DATA_PATH)
	@mkdir -p $(MYSQL_VOLUME_PATH)

build: volumes
	@echo "$(GREEN)<+> BUILDING CONTAINERS <+> $(COLOR_OFF)"
	@docker compose -f $(DOCKER_COMPOSE) up -d

mysql: volumes
	@echo "$(GREEN)<+> BUILDING MYSQL CONTAINER <+> $(COLOR_OFF)"
	@docker compose up mysql

php: volumes
	@echo "$(GREEN)<+> BUILDING PHP CONTAINER <+> $(COLOR_OFF)"
	@docker compose up php

phpmyadmin: volumes
	@echo "$(GREEN)<+> BUILDING PHPMYADMIN CONTAINER <+> $(COLOR_OFF)"
	@docker compose up phpmyadmin

restart: down
	@echo "$(GREEN)<+> STARTING CONTAINERS <+> $(COLOR_OFF)"
	@docker compose -f $(DOCKER_COMPOSE) up -d

stop:
	@echo "$(GREEN)<+> STOPPING CONTAINERS <+> $(COLOR_OFF)"
	@docker compose -f $(DOCKER_COMPOSE) stop

down: stop
	@echo "$(GREEN)<+> DELETING BUILD <+> $(COLOR_OFF)"
	@docker compose -f $(DOCKER_COMPOSE) down -v
	
remove_data:
	@echo "$(GREEN)<+> REMOVING DATA <+> $(COLOR_OFF)"
	@rm -rf $(DATA_PATH)
	@rm -rf $(LOGS_PATH)

destroy: down remove_data
	@echo "$(GREEN)<+> REMOVING ALL IMAGES <+> $(COLOR_OFF)"
	@docker system prune -af

re: destroy build
	@echo "$(GREEN)<+> RESETTING CONTAINERS <+> $(COLOR_OFF)"

.PHONY: all build up stop remove_data down destroy restart volumes re