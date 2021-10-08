# Mapping API

## Requirements

Docker and docker-compose.

## Dependencies

## Installation

Follow the instructions found in
the [documentation](http://docs.projectone.mgcorp.co/docs/development-environment/index.html)

## Swagger UI
You can access the swagger definition by clicking on the desired environment link:
 
|Environment|Link|
|-----------|----|
| Local Dev | http://nginx/api/documentation |

## Create database/migrate/seed on dev
```
docker exec -it oneportal_catalog_api_php php artisan db:create
docker exec -it oneportal_catalog_api_php php artisan migrate:fresh
docker exec -it oneportal_catalog_api_php php artisan db:seed
```
