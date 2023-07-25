# Documentation Of Exchange Rate App

### About

-   App receives exchange rate info and storees returned data to the txt file.

### Server Requirements

1. PHP (ver >= 8.1)
2. Composer (ver >= 2.2.0)
3. Git
4. MySQL (OPTIONAL for failed_jobs, ver >= 5.7)

### Installation and Configuration

1. `git clone https://github.com/shoxrux1996/exchange_rate.git` - clone the project

2. `$ cp .env.example .env` - copy .env.example to .env file inside root folder

3. `$ php artisan key:generate` - generate key.

4. `$ composer install` - installing composer packages.

5. OPTIONAL (for failed jobs): Create a database in mysql and update DB credentials in .env

6. OPTIONAL: You can update queue driver to "database", "beanstalkd", "sqs", "redis", "null. By default it's **sync**

### Quick Usage

`$ $ php artisan queue:work {quote} {base} {days}` - Call this command to collect the rates with given range of days.

Arguments:
**quote** - **Required** Quote currency code (ISO 4217 standard).
**base** - **Optional**. Base currency code. (ISO 4217 standard). By default it's RUR
**days** - **Optional**. Store data with given range of days. By default it's 1, and stores today's rate data.

> **_NOTE:_** before calling this make sure you have running queue worker. In order to run in you local, call `$ php artisan queue:work` command.

After running `$ php artisan app:exchange-rate-data-collection` command, it will store the data inside folder with path: `storage/app/Rates/`.

You can also check the output inside `storage/logs/laravel.log` file.

### Example

1. At first we run our queue: `$ php artisan queue:work`
2. call `$ php artisan app:exchange-rate-data-collection UZS RUR 180`

> **_NOTE:_** This app supports only Base Quote = RUR, it has only have one client Cbr. In order to add more Client, just add your custom client inside `app/Clients` and update the factory `app/Clients/ClientFactory.php` as well
