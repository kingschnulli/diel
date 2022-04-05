## Quick install

### Requirements

* Docker
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
```

Modify .env file to your liking, nothing to do for dev environment.

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
