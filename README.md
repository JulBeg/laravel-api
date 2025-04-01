# Laravel API

A dockerized Laravel REST API starter :

## Features

- Laravel 12
- Sanctum Session Auth
- Email verification
- Telescope
- Postman Setting
- Feature tests

## Installation

1. Install docker
2. Clone the repo
3. Install composer packages `docker compose run --rm api composer install`
4. Launch the API `docker compose up -d`

Import Postman's setup files from `./.postman` to test the api auth endpoints.

## Usage/Examples

- **Enter the container:** `docker compose exec api sh`
- **Run tests**: `php artisan tests`
- **Create a new Resource:** `php artisan make:model -cfmrRs --api --test`
- **Telescope:** `http://localhost:8000/telescope`
