## Participatory Budget

Start project
```
docker-compose up -d
```

Install dependecies on backend
```
docker exec -it participatory_webapp composer install --no-ansi --no-dev --no-interaction --no-plugins --no-progress --no-scripts --no-suggest --optimize-autoloader
```

Migrate database from Doctrine
```
docker exec -it participatory_webapp composer db-update
```
