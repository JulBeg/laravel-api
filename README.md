# Laravel API

A dockerized Laravel REST API starter :

## Features

- Laravel 12
- Sanctum Session and Token based Auth
- Email verification
- Telescope
- Postman Setting
- Feature tests

## Installation

1. Install docker
2. Clone the repo
3. Install composer packages `docker compose run --rm api composer install`
4. Copy the `.env.example` file to `.env` and edit it
5. Generate the APP_KEY `docker compose run --rm api php artisan key:generate`
6. Run migrations `docker compose run --rm api php artisan migrate`
7. Launch the API `docker compose up -d`

Import Postman's setup files from `./.postman` to test the api auth endpoints.

## Usage/Examples

- **Enter the container:** `docker compose exec api sh`
- **Run tests**: `php artisan tests`
- **Create a new Resource:** `php artisan make:api-resource <name>`
- **Telescope:** `http://localhost:8000/telescope`

## CLI

The project includes a convenient command-line tool `lvl` for managing the Docker environment:

```bash
# Run Artisan commands
./lvl artisan <command>

# Manage containers
./lvl start
./lvl stop
./lvl restart
./lvl down # with -v to destroy volumes

# Development tools
./lvl composer <command>
./lvl test
./lvl pint
./lvl stan

# Docker management
./lvl build [image]
./lvl logs [container]
./lvl exec [command]

# Project management
./lvl install
./lvl update
./lvl destroy
```

For a complete list of commands and options, run `./lvl` without arguments.
