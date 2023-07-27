# Run application steps
- install dependencies `composer install`
- create MySQL database `shorturl`
- create `.env` file based on `.env.example` and set `DB_USERNAME` and `DB_PASSWORD`
- run migrations: `php artisan migrate`
- generate app key: `php artisan key:generate`
- start application: `php artisan serve`
