<p align="center"><a href="https://laravel.com" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400" alt="Laravel Logo"></a></p>

# User Accounts service

Responsible for storing and managing user information, as well as verifying the identity of users and ensuring that they are authorized to access the website and its features. This could involve creating and managing user accounts, as well as implementing mechanisms for verifying users' identities, such as by using passwords or other forms of authentication.

# Installation

Before cloning the repository:
- create a `user_accounts` schema in your MySQL database

Upon cloning this repository:
- `cd` into the repository
- run `composer update` then `composer install` to generate dependencies in the vendor folder
- change `.env.example` to `.env`
- run `php artisan key:generate`
- configure the `.env`  with your MySQL configurations

# Usage

Upon installation:
- run `php artisan migrate:fresh` to create tables in database
- run `php artisan serve --port=8001` to start the server on a local environment
