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
3. Install application `./lvl install`
4. Launch the API `./lvl start`

Import Postman's setup files from `./.postman` to test the api auth endpoints.

## Usage/Examples

- **Enter the container:** `docker compose exec api sh`
- **Run tests**: `php artisan tests`
- **Create a new Resource:** `php artisan make:api-resource <name>`
- **Telescope:** `http://localhost:8000/telescope`

### API Resource Generation

You can generate a new resource with the artisan command:

```bash
./lvl artisan make:api-resource {resource_name}
```

It will generate the following files:

- Model
- Migration
- Seeder
- Controller
- Requests
- Resource
- Routes
- Tests

You can also ask cursor to generate everything for you, just ask for a new resource with the details of its attributes and relationship and it will generate everything for you using the rule in `./.cursor/rules/generate-api-resource.mdc`:

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
