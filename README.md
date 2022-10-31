# Laravel Code Challenge

This code test involves performing work on an existing Laravel project.
The task is split into three sub-categories and shouldn't take longer than 2-4 hours of your time.

### Restrictions and Requirements
1. Please do **NOT** use any JS/AJAX to solve this challenge but build it in PHP.
2. This challenge doesn't focus on the UI. Do not spend too much time on layout/css.
3. You should focus on code quality and structure. If possible and timely reasonable, also add tests.
4. Wherever possible and reasonable, try to follow the [SOLID principles](https://en.wikipedia.org/wiki/SOLID)

### The Challenge
The assignment is to add a column to the database and related endpoints

1. `get` needs to include the new column
2. `create`/`update` needs to be able to change the value of the column asserting some validation rule
3. Documentation should be updated so Swagger can be generated and used to smoke test

### Hints
This repository has been set up for you to start right away. We are using [Laravel Sail](https://laravel.com/docs/9.x/sail) to ensure that 
this code challenge can be run locally on your machine, regardless of your installed system environment.
- The routes can be found and configured in the file `routes/web.php`.
- A first controller can be found here: `app/Http/Controllers/SearchController.php`.
- The views can be found under `resources/views`.
- Publicly accessible assets can be placed into the `public/` folder and its sub-directories.
