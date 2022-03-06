# AC1 Proj Web 2 corrent al Docker Local Enviroment 2 de l'assignatura
# Local Environment
> Using Docker for our local environment

## Requirements

1. Having [Docker installed](https://www.docker.com/products/docker-desktop) (you will need to create a Hub account)
2. Having [Git installed](https://git-scm.com/downloads)

## Installation

1. Clone this repository into your projects folder using the `git clone` command.

```
       Name                     Command               State                 Ports              
-----------------------------------------------------------------------------------------------
pw_local_env-admin   entrypoint.sh docker-php-e ...   Up      0.0.0.0:8080->8080/tcp           
pw_local_env-db      docker-entrypoint.sh mysqld      Up      0.0.0.0:3330->3306/tcp, 33060/tcp
pw_local_env-nginx   /docker-entrypoint.sh ngin ...   Up      0.0.0.0:8030->80/tcp             
pw_local_env-php     docker-php-entrypoint php-fpm    Up      9000/tcp, 0.0.0.0:9030->9001/tcp
```
