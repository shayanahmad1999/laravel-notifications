# Laravel api Setup

This README will guide you through setting up and running the Laravel project locally.

## Prerequisites

Ensure the following tools are installed on your system:
🔧 Tech Stack:

-   PHP >= 8.4
-   Laravel = 12
-   Composer
-   Node.js >= 24.x
-   NPM >= 8.x
-   MySQL or any supported database

## Installation & Setup

Follow the steps below to get started:

```bash
# Clone the repository
git clone https://github.com/shayanahmad1999/laravel-vue-full-stack-laravel-api.git
cd folder_name

# Install PHP dependencies
composer install

# Initialize and install Node.js dependencies
npm install

# Build frontend assets
npm run build

# Run the development server (optional during setup)
npm run dev

# Copy and set up the environment configuration
cp .env.example .env

# Generate application key
php artisan key:generate

# Setting up the .env file
After setting up the .env file, please ensure that you have
MAIL_MAILER=smtp
MAIL_SCHEME=null
MAIL_HOST=smtp_host
MAIL_PORT=smtp_port
MAIL_USERNAME=smtp_username
MAIL_PASSWORD=smtp_password
MAIL_FROM_ADDRESS="hello@example.com"
MAIL_FROM_NAME="${APP_NAME}"

Also set up your databse
DB_CONNECTION=sqlite
DB_HOST=host
DB_PORT=port
DB_DATABASE=datbase_name
DB_USERNAME=datbase_username
DB_PASSWORD=datbase_password

# Run database migrations
php artisan migrate

# Run the development server
php artisan serve
npm run dev

```
