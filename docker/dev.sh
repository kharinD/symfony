#!/usr/bin/env bash

if [[ ! -f .env ]]
then
    echo 'File .env doesn`t exists' >&2
    exit 1
fi

source .env

ACTION=$1

if ! [[ -x "$(command -v docker)" ]]
then
  echo 'Error: Docker not installed. Please install docker from official site, not from your OS repo!' >&2
  exit 1
fi

if ! [[ -x "$(command -v docker-compose)" ]]
then
  echo 'Error: docker-compose not installed. Please install docker-compose from official site, not from your OS repo!' >&2
  exit 1
fi

pull() {
    mkdir -p "${HOME}"/composer/cache && chmod 777 "${HOME}"/composer/cache || (echo "Unable to create composer cache dir in ${HOME}" && exit 1);

    docker-compose -f docker-compose-${APP_ENV}.yml pull
}

down() {
    docker-compose -f docker-compose-${APP_ENV}.yml down --remove-orphans
}

up() {
    docker-compose -f docker-compose-${APP_ENV}.yml up --detach
}

install() {
    docker exec \
        -it \
        -e COMPOSER_CACHE_DIR=/tmp/cache \
        symfony-app composer install
}

update() {
        docker exec \
        -it \
        -e COMPOSER_CACHE_DIR=/tmp/cache \
        symfony-app composer update
}

if [[ -z ${ACTION} ]]
  then
    down
    up
    install
fi

case ${ACTION} in
     pull)
          pull
          exit;;
     down)
          down
          exit;;
     up)
          up
          exit;;
     install)
          install
          exit;;
     update)
          update
          exit;;
esac
