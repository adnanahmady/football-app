#!make
mainShell=bash
mainService=backend

define default
$(if $(1),$(1),$(2))
endef

build:
	# o is an acronym for options,
	# you can add more options to
	# the build command using like
	# make build o=--no-cache or
	# make build o="--no-cache --remove-orphans"
	@docker-compose build ${o}

up:
	@touch .backend/bash_history
	@docker-compose up -d ${options}

down:
	@docker-compose down

destroy:
	@docker-compose down --volumes --remove-orphans

ps:
	@docker-compose ps

status: ps

shell:
	@docker-compose exec $(call default,${service},${mainService}) $(call default,${run},${mainShell})

logs:
	@docker-compose logs ${service}
