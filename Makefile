#!make
mainShell=bash
mainService=backend

define default
$(if $(1),$(1),$(2))
endef

# This function receives two parameters,
# first one is target service and the
# second is running command, if target
# service is not specified then the
# main service will be picked as the target
# service, if the desired command to run is
# not specified then the main shell will get
# run as the command
define execute
@docker-compose exec $(call default,$(1),${mainService}) $(call default,$(2),${mainShell})
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
	$(call execute,${service},${run})

test:
	$(call execute,${service},composer test)

logs:
	@docker-compose logs ${service}
