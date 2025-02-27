## Simple Whois project

![Whois Example](whois-example-image.png)

It is writed on Laravel and help users to find open information about domains.

Requirements:
- PHP 8.2 - 8.4
- Composer
- MySQL
- Laravel 12

Before run the project you should:
 - create .env file from .env.example 
 - set the mysql database connection (create DB Schema and add it to .env file)

For run the project you should run the following commands:

```bash
composer install
php artisan migrate
php artisan key:generate
php artisan serve
```
Go to the link from the console (for example http://127.0.0.1:8000)
