## Prerequisites

- [PHP ^8.3](https://www.php.net/downloads.php)
- [Composer](https://getcomposer.org/download/)
- [Docker Desktop](https://www.docker.com/products/docker-desktop/)
- [Git](https://git-scm.com/downloads)

## Installation

- Clone IP manager repository
```
git clone git@github.com:geraldarcega/ip-manager.git
```
- Create environment file
```
cd ip-manager
cp .env.example .env
```
- Update environment file, replace the value of the following variables
```
DB_USERNAME=sail
DB_PASSWORD=password
```
- Install packages
```
composer install
```
- Build Docker container (make sure to open your Docker Desktop application before running the command)
```
./vendor/bin/sail up -d
```
- Migrate database
```
./vendor/bin/sail artisan migrate
```
- Seed database
```
./vendor/bin/sail artisan db:seed
```
- Add custom domain to your host file
```
127.0.0.1    ip-manager.local
```

## Tests
- To run the test
```
./vendor/bin/sail artisan test
```

## Troubleshooting
- Make sure to set the correct url of the FRONTEND_URL in .env file including the port number.
```
example: FRONTEND_URL=http://localhost:5173
```