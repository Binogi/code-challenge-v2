# Laravel Code Challenge

This code test involves performing work on an existing Laravel project.
The task is split into three sub-categories and shouldn't take longer than 2-4 hours of your time.

### Restrictions and Requirements
1. This challenge requires Docker to be installed on your system. The easiest way to accomplish this is to [install Docker Desktop](https://www.docker.com/).
2. You should focus on code quality and structure. If possible, timely, and reasonable, also add tests.
3. Wherever possible, try to follow the [SOLID principles](https://en.wikipedia.org/wiki/SOLID).

### Setup
This repository has been set up for you to start right away. We are using Docker to ensure that
this code challenge can be run locally on your machine, regardless of your installed system environment.
- The project can be brought up by running the following commands from the root directory of the project:
  - `docker-compose up --remove-orphans`
  - `docker-compose run --rm php composer install`
  - `docker-compose run --rm php /var/www/artisan migrate:fresh --seed`
  - `docker-compose run --rm php /var/www/artisan l5-swagger:generate`

### The Challenge
You have been given access to a list of users. 
The assignment is to add a column named `nickname` (via a migration) to the database as well as updating the related endpoints.

1. The GET request needs to include the new column
2. The POST request and the PUT request need to be able to change the value of the column asserting the following validation rules:
   - A valid nickname must be unique among users
   - A valid nickname must be shorter than 30 characters
3. Documentation should be updated so Swagger can be generated and used to smoke test
   - We are using the open-source package [L5-Swagger](https://github.com/DarkaOnLine/L5-Swagger) to generate OpenAPI Swagger  

### Hints
- The OpenAPI Swagger documentation can be generated on demand by running `./vendor/bin/sail artisan l5-swagger:generate` in the root directory of the project
  - This documentation can be viewed by navigating to [http://localhost:7777/api/documentation](http://localhost:7777/api/documentation)
- Don't worry about authentication
- Tests can be run by running the following commands:
  - `docker exec -it mysql bash -c "mysql -u root -ppassword -e \"DROP DATABASE IF EXISTS testing; CREATE DATABASE testing\""`Creates the test DB
  - `docker-compose run --rm php php /var/www/artisan test` (run the tests)
