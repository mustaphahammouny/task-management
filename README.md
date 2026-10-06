# Task Management

A task and project management application built with Laravel and Vue.

## Requirements

- **PHP:** 8.3 or newer, with the PDO MySQL extension enabled.
- **MySQL:** a running server and a database for the application.
- **Composer:** 2.
- **Node.js:** 22.
- **npm:** 10.

Check your installed versions:

```sh
php --version
composer --version
node --version
npm --version
```

## Local setup

Run these commands from the project directory after cloning the repository.

1. Install the dependencies:

   ```sh
   composer install
   npm install
   ```

2. Create the environment file and generate the application key:

   ```sh
   php -r "file_exists('.env') || copy('.env.example', '.env');"
   php artisan key:generate
   ```

3. Start MySQL and create a database named `task_management` using your database manager (for example, phpMyAdmin). Update `.env` with your local MySQL credentials:

   ```dotenv
   DB_CONNECTION=mysql
   DB_HOST=127.0.0.1
   DB_PORT=3306
   DB_DATABASE=task_management
   DB_USERNAME=
   DB_PASSWORD=
   ```

   Replace the username and password with your MySQL credentials, then create the tables:

   ```sh
   php artisan migrate
   ```

4. Start the development servers:

   ```sh
   composer run dev
   ```

   Open the local application address printed in the terminal. Keep the command running while developing.

## Useful commands

```sh
# Add sample users, projects, and tasks (optional)
php artisan db:seed

# Build frontend assets
npm run build

# Run the test suite
php artisan test --compact
```
