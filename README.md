# [](https://repository-images.githubusercontent.com/435510957/9387fb55-6a64-44f0-9574-aecee2b0e3cb)

# Coin Embed

Use CoinEmbed to create embeddable widgets for easy cryptocurrency payments. CoinEmbed connects directly to Coinbase Commerce, Stripe, or a Nano wallet so all payments come directly to you!

This repository provides the entire CoinEmbed dashboard to allow you to create widgets for payments privately for your own company, or make it public to provide a smooth experience to provide payment widgets to users around the world

Most of the project is built in Laravel, so you will want to make sure your server has the appropriate specs to run PHP/Laravel applications: https://laravel.com/docs/8.x/installation

Laravel Forge (https://forge.laravel.com) provides easy server management for Laravel and PHP application setup with services like Digital Ocean

## Installation

### Nano Node

To start, you will need a Nano node available to interact with:

- Docker https://github.com/lephleg/nano-node-docker
- Enable node for public access by editing docker-compose (https://github.com/lephleg/nano-node-docker/issues/5)
- You will likely want to update network permssions to lock this down to the specific app ip address

## Project Installation

Begin by copying the environment file:

```
cp .env.example .env
```

Create a new database and then update the file with the appropriate keys and database information.

Then you can finish by installing PHP dependencies via composer and run migrations on your database

```
composer install
php artisan key:generate
php artisan migrate
```

You should provide NGINX configuration to point to your `/publc` directory as the root site. ie:

```
server {
    ...
    server_name coinembed.com;
    root /path/to/coinembed.com/public;
    ...
    index index.html index.htm index.php;
    charset utf-8;

    location / {
        try_files $uri $uri/ /index.php?$query_string;
    }

    location = /favicon.ico { access_log off; log_not_found off; }
    location = /robots.txt  { access_log off; log_not_found off; }

    access_log off;
    error_log  /var/log/nginx/coinembed.com-error.log error;

    error_page 404 /index.php;

    location ~ \.php$ {
        fastcgi_split_path_info ^(.+\.php)(/.+)$;
        fastcgi_pass unix:/var/run/php/php7.4-fpm.sock;
        fastcgi_index index.php;
        include fastcgi_params;
    }

    location ~ /\.(?!well-known).* {
        deny all;
    }
}
```

## Frontend Setup

```
yarn
yarn run prod
```

Once these steps are complete, you should be able to view the homepage at your given IP / Domain

## Cron / Scheduled Tasks

A cron is required run to ensure scheduled tasks are completed. These include checking completed transactions and keeping the price of Nano updated.

You can view the scheduled tasks and how often they run in: `app\Console\Kernel.php`

To schedule these tasks on your server, your cron command should run every second and look something like:

`php /path/to/coinembed.com/artisan schedule:run`

## Queue

By default, tasks will perform in sync. However, to enable queuing you can view more details here: https://laravel.com/docs/8.x/queues
