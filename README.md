# Laravel API

A dockerized Laravel REST API starter :

## Features

- Laravel 11
- Sanctum Session Auth
- Email verification
- Telescope
- Postman Setting
- Feature tests

## Installation

Install docker then run `docker compose up -d`

Import Postman's setup files from `./.postman` to test the api auth endpoints.

## Usage/Examples

- **Enter the container:** `docker compose exec api sh`
- **Run tests**: `php artisan tests`
- **Create a new Resource:** `php artisan make:model -cfmrRs --api --test`
- **Telescope:** `http://localhost:8000/telescope`
