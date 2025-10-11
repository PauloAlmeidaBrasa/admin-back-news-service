📰 Admin Back News Service API

This is a Laravel 10 RESTful API that powers the Admin Back News Service, providing backend functionality for managing news content.
It uses JWT authentication for secure access and supports both Docker and local development environments.

🚀 Features

Laravel 10 Framework

JWT Authentication (JSON Web Tokens)

Database migrations and seeders

Configurable API versioning

Dockerized environment for easy setup

Compatible with the orchestrator-news
 repository

⚙️ Requirements

If running locally (without Docker), make sure you have:

PHP 8.2+

Composer

MySQL or MariaDB

OpenSSL, PDO, Mbstring, Tokenizer, XML extensions enabled

If running with Docker, you only need Docker and Docker Compose installed.

🧩 Environment Setup

Before running the API, you must create a .env file in the project root.

You can copy the example:   cp .env.example .env

Then, edit the file and set at least the following variables:

API_VERSION=v1

DB_CONNECTION=mysql

DB_HOST=127.0.0.1

DB_PORT=3306

DB_DATABASE=your_database

DB_USERNAME=your_user

DB_PASSWORD=your_password

🐳 Running with Docker

Build the image

docker build -t admin-back-news-service .

Run the container

docker run -d -p 8000:8000 --name admin-back-news-service admin-back-news-service

Then access the API at:
👉 http://0.0.0.0:8000

🧭 Running through the Orchestrator

You can also run this API through the orchestrator-news https://github.com/PauloAlmeidaBrasa/news-orchestrator
 repository using docker-compose:

git clone https://github.com/PauloAlmeidaBrasa/news-orchestrator.git

cd orchestrator-news

git submodule update --init backend-news-service

docker compose up --build

💻 Running Locally (Without Docker)
1. Install dependencies
composer install

2. Generate application key
php artisan key:generate

3. Run migrations and seed the database
php artisan migrate --seed

4. Start the local server
php artisan serve


The API will be available at:
👉 http://0.0.0.0:8000

🔐 Authentication

This API uses JWT (JSON Web Token) for authentication.
After registering or logging in, include your token in the Authorization header:

Authorization: Bearer <your_token_here>

🧱 Database Seeding

To populate the minimum required data:

php artisan db:seed


You can also re-run migrations with fresh data:

php artisan migrate:fresh --seed

🧪 Testing

Run the test suite using:

php artisan test
