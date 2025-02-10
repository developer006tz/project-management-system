# Project Setup Guide

## Prerequisites

Before starting, ensure you have the following installed on your system:
- Docker
- Docker Compose
- PostgreSQL client (for local database access)

## Initial Setup

1. Clone the repository to your local machine.

2. Create your environment file:
```bash
cp .env.example .env
```

3. Configure the environment variables in the `.env` file according to your needs.

## Docker Setup and Management

### Starting the Application

To build and start all containers:
```bash
docker-compose up --build
```
### Accessing the Application Container

To access the application container's command line:
```bash
docker-compose exec app bash
```

Use this to run commands within the container environment.

### Database Management

#### Database Connection
To connect to the PostgreSQL database:
```bash
psql -h localhost -p 5434 -U postgres -d project_management
```

#### Running Migrations
To run database migrations:
```bash
docker-compose exec app php artisan migrate
```

## Development Workflow

1. Start the development environment:
```bash
docker-compose up --build
```

2. Make your code changes - the application will automatically reload changes.

3. Run migrations if you've made database changes:
```bash
docker-compose exec app php artisan migrate
```

4. To stop development:
```bash
docker-compose down
```

## Testing

### Running Tests

To run all tests in the container:
1. create .env.testing
2. configure database details then
```bash
docker-compose exec app php artisan test
```

### Api docs
1. are included in Project management system-API Documentation #reference.postman_collection.json
