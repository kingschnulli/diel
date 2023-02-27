## Quick install

### Requirements

* Docker (for local environment, otherwise LAMPP Stack)
* Composer
* NodeJS
* PHP >= 8.0

See Laravel Sail requirements for more information

### Get sources

``` bash
$ git clone https://github.com/kingschnulli/diel
$ cd diel
$ composer install
$ npm install
$ cp .env.example .env
$ npm run prod
```

Modify .env file to your liking, 

Set `ENV=prod` for production environment.

### Setup production environment

``` bash
$ php artisan migrate:fresh
$ php artisan db:seed --class=ProductionDatabaseSeeder
$ php artisan key:generate
```

#### Default Admin User

Username: admin@example.org <br/>
Password: password

Change password for production environment.

### Running locally with docker (sail)

``` bash
$ vendor/bin/sail up -d
$ vendor/bin/sail artisan key:generate
$ vendor/bin/sail artisan migrate:fresh --seed
```

You should be able to navigate to http://localhost then, login as following from seeds:

#### Enduser:

Username: user@example.org <br/>
Password: password

#### Admin

Username: admin@example.org <br/>
Password: password
