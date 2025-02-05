docker-compose exec app bash
docker-compose up --build
docker-compose down -v
psql -h localhost -p 5434 -U postgres -d project_management
docker-compose exec app php artisan migrate
