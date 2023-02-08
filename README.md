<p align="center"><a href="https://laravel.com" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400" alt="Laravel Logo"></a></p>

# User Accounts service

Responsible for storing and managing user information, as well as verifying the identity of users and ensuring that they
are authorized to access the website and its features. This could involve creating and managing user accounts, as well
as implementing mechanisms for verifying users' identities, such as by using passwords or other forms of authentication.

> Access the API on https://account-api.klenze.com.au

## Getting Started Locally

To get started, you'll need to have the following software installed on your local machine:

- MySQL (local server or DBaaS)
- PHP 8
- Composer

Once you have MySQL, create a schema in the MySQL DB called `accounts`.

Once you have PHP 8 and Composer installed, clone this repository to your local machine.

```bash
$ git clone "https://github.com/CleanCut-DevOps/account-api.git"
```

Next, navigate to the root directory of the project and install the dependencies:

```bash
$ cd account-api

$ composer install -q -n --no-ansi --no-scripts --no-progress --prefer-dist
```

Next, copy the `.env.example` file to `.env`.

1. Update the database credentials to connect to your MySQL database
2. Set the URLs of all other services listed in the `.env` file to the URLs where the other services can be accessed
   from.
3. Generate a new laravel key.

```bash
$ cp .env.example .env

# Update the database credentials in the .env file

# Update the URLs of all other services

$ php artisan key:generate

$ php artisan optimize
```

Next, run the database migration to create the tables in the database.

```bash
$ php artisan migrate:fresh --seed
```

Finally, start the development server.

```bash
$ php artisan serve --port=8001
```

The application will now be running on http://localhost:8001.

If you're running this on a server, point the server to the entry point: `public/index.php`.
