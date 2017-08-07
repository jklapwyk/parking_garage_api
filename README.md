# Parking Garage API

This the parking garage api created by Jamie Klapwyk.

## Setup instructions

1 . Install Docker

- Mac - https://docs.docker.com/docker-for-mac/install/
- Windows 10 - https://docs.docker.com/docker-for-windows/install/
- Windows 8, 7 - https://docs.docker.com/toolbox/toolbox_install_windows/

2 . Make sure Docker is running

3 . Clone this repository

4 . In terminal navigate to the docker directory
```
cd [path to application]/tools/docker
```

5 . Run the docker compose command
```
docker-compose up -d
```

6 . Run bash in the stratus container
```
docker exec -i -t parkingapi /bin/bash
```

7 . Navigate to the Parking API application in the Docker container
```
cd /var/www/html
```

8 . Run composer install. This will download the dependancies for the project
```
php composer.phar install
```

9 . Copy the `.env.example` file to `.env`
```
cp .env.example .env
```

10 . Run the App Key Generate command
```
php artisan key:generate
```

11 . Change these lines in the `.env` file from

```
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=homestead
DB_USERNAME=homestead
DB_PASSWORD=secret

MAIL_DRIVER=smtp
MAIL_HOST=smtp.mailtrap.io
MAIL_PORT=2525
MAIL_USERNAME=null
MAIL_PASSWORD=null
MAIL_ENCRYPTION=null
```

to

```
DB_CONNECTION=mysql
DB_HOST=parkingapidb
DB_PORT=3306
DB_DATABASE=parkingapi
DB_USERNAME=dbuser
DB_PASSWORD=ZhW1f3znrj43nk2x

MAIL_DRIVER=smtp
MAIL_HOST=smtp.mailtrap.io
MAIL_PORT=2525
MAIL_USERNAME=[mailtrap credentials]
MAIL_PASSWORD=[mailtrap credentials]
MAIL_ENCRYPTION=null
```

12 . Copy the `.env` file to `.env.testing`
```
cp .env .env.testing
```


13 . Change these lines in the `.env.testing` file from

```
APP_ENV=local

DB_HOST=parkingapidb

DB_DATABASE=parkingapi
```

to

```
APP_ENV=testing

DB_HOST=parkingapitestdb

DB_DATABASE=parkingapitest
```

14 . Run the database migration
```
php artisan migrate
```

15 . You should be able to run the application by opening a browser and going to `http://localhost:8280`

16 . To test out the API there's a Postman collection in the  `tool/postman` directory

## PHP Unit Tests

1 . You can also run the PHPunit tests by connecting to the container if the container is running
```
docker exec -i -t parkingapi /bin/bash
```

2 . Navigate to the Parking API application in the Docker container
```
cd /var/www/html
```

3 . Run the php unit tests
```
./vendor/bin/phpunit
```
