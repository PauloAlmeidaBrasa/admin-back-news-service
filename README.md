Admin Back News Service API
A Laravel 10-based REST API for news management with JWT authentication.

Prerequisites
PHP 8.2 or higher

Composer (for non-Docker setup)

MySQL/PostgreSQL/SQLite database

Docker (optional, for containerized setup)

Environment Configuration
Create a .env file in the root directory and configure the following variables:
# API Configuration
API_VERSION=1.0

# Database Configuration
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=your_database_name
DB_USERNAME=your_username
DB_PASSWORD=your_password

# JWT Configuration (generate with: php artisan jwt:secret)
JWT_SECRET=your_jwt_secret_key

Installation & Setup

Option 1: Using Docker (Recommended)
Standalone Container:

# Build the Docker image
docker build -t admin-back-news-service .
# Run the container
docker run -d -p 8000:8000 --name admin-back-news-service admin-back-news-service

Using Docker Compose (from orchestrator-new repository): --> https://github.com/PauloAlmeidaBrasa/news-orchestrator
# Navigate to the orchestrator repository
cd orchestrator-new

# Start the services
docker-compose up -d

Option 2: Local Development (without Docker)
Install dependencies:

composer install

Generate application key:

php artisan key:generate

Generate JWT secret:

php artisan jwt:secret

Run database migrations and seeders:

php artisan migrate --seed
Start the development server:

php artisan serve
The API will be available at http://0.0.0.0:8000

Database Setup
After configuring your database in the .env file, run:


# Run migrations
php artisan migrate

# Run seeders to populate with minimum data
php artisan db:seed

API Endpoints
The API follows RESTful conventions and includes JWT authentication. All endpoints are prefixed with the API version (e.g., /api/v1/).

Authentication Endpoints
POST /api/v1/login - User login
That endpoint is not covered by jwt, It is used to acquire the JWT token. 

Security
This API uses JWT (JSON Web Tokens) for authentication. Include the token in the Authorization header for protected routes:

text
Authorization: Bearer {your_jwt_token}
Development
Framework: Laravel 10

Authentication: JWT (tymon/jwt-auth)

Database: Eloquent ORM with migrations

Containerization: Docker
