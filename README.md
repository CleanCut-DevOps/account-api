<p align="center"><a href="https://laravel.com" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400" alt="Laravel Logo"></a></p>

# User Accounts service

Responsible for storing and managing user information, as well as verifying the identity of users and ensuring that they
are authorized to access the website and its features. This could involve creating and managing user accounts, as well
as implementing mechanisms for verifying users' identities, such as by using passwords or other forms of authentication.

## Getting Started

To get started, you'll need to have the following software installed on your local machine:

- MySQL
- PHP 8
- Composer

Once you have MySQL, create a schema in the MySQL DB called `user_accounts`.

Once you have PHP 8 and Composer installed, clone this repository to your local machine.

```bash
git clone "https://github.com/CleanCut-DevOps/user-accounts-service.git"
``` 

Next, navigate to the root directory of the project and install the dependencies:

```bash
cd user-accounts-service

composer update

composer install
```

Next, copy the `.env.example` file to `.env`.

1. Update the database credentials to match your local MySQL database
2. Update the URLs of all other services listed in the `.env` file to match the URLs of the services running on your
   local machine.
3. Generate a new laravel key.

```bash
cp .env.example .env

// Update the database credentials in the .env file

// Update the URLs of all other services

php artisan key:generate
```

Next, run the database migrations to create the tables in the database.

```bash
php artisan migrate:fresh
```

Finally, run the application.

```bash
php artisan serve --port=8001
```

The application will now be running on http://localhost:8001.
