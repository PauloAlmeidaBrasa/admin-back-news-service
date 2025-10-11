🚀 Admin Back News Service API
<div align="center">
A robust Laravel 10 REST API for news management with JWT authentication
</div>
📋 Table of Contents
Features

Prerequisites

Quick Start

Environment Setup

Installation

API Documentation

Development

✨ Features
Feature	Description
🔐 JWT Authentication	Secure token-based authentication system
📰 News Management	Full CRUD operations for news articles
🐳 Docker Support	Containerized development and production
🗄️ Database Migrations	Structured database schema management
🌱 Data Seeding	Pre-populated with essential data
🔒 Security	Built-in security best practices

🛠 Prerequisites
Before you begin, ensure you have:

🐘 PHP 8.2 or higher

📦 Composer (for local development)

🗄️ MySQL/PostgreSQL database

🐳 Docker (optional, for containerized setup)

🚀 Quick Start
Option 1: 🐳 Docker Setup (Recommended)
Standalone Container:

# 🔨 Build the Docker image
docker build -t admin-back-news-service .

# 🏃 Run the container
docker run -d -p 8000:8000 --name admin-back-news-service admin-back-news-service
Using Docker Compose:

# 📁 Navigate to the orchestrator repository
cd orchestrator-new

# 🚀 Start all services
docker-compose up -d
Option 2: 💻 Local Development

# 1. 📦 Install dependencies
composer install

# 2. 🔑 Generate application key
php artisan key:generate

# 3. 🎯 Generate JWT secret
php artisan jwt:secret

# 4. 🗄️ Setup database
php artisan migrate --seed

# 5. 🏃 Start server
php artisan serve


⚙️ Environment Setup
Create a .env file and configure:

env
# 🚀 API Configuration
API_VERSION=v1

# 🗄️ Database Configuration
DB_CONNECTION=mysql
DB_HOST=db
DB_PORT=3306
DB_DATABASE=news_back_db
DB_USERNAME=laravel
DB_PASSWORD=123

# 🔐 JWT Configuration
JWT_SECRET=your_generated_jwt_secret

🗄️ Database Setup
# 🎯 Run migrations
php artisan migrate

# 🌱 Seed with minimum data
php artisan db:seed
🛠 Development
Running Tests

php artisan test
Generating Documentation
