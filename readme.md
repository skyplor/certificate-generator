<p align="center"><img src="https://www.kalpvaig.com/assets/img/corporate/logo.png"></p>

## About Certificate Generator

This certificate generator is based on the top of Laravel a web application framework and AdminLTE a third party admin panel library.

## Installing

### Composer
Ensure you have composer installed

`brew install composer`

Run the following command to install the dependencies

`composer install`

### Database Setup
Run following command to setup the database

`php artisan migrate`

Edit Admin Username and password from database/seeders/AdminSeeder.php and Run following command

`php artisan db:seed`

## Using
Run the following command
`php artisan serve`

Open http://localhost:8000/login to open the login page

And http://localhost:8000/ For public view to verify certificate by using numbers