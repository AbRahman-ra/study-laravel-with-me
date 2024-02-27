# 01 - Laravel Installation and Architecture Concepts


## General

- Composer is written in Laravel
- PHP Can be used as a scripting language outside of web development
  - Scripting Languages are languages that are written as automated scripts
  - We can execute PHP scripts from the terminal using `php file.php`
  - We can run PHP directly in the terminal using `php` then write our code
  - We can create desktop applications using PHP

## Your First Laravel Project

- When we write `composer create-project laravel/laravel`
  - The first `laravel` is the author, or more technically speaking **The Vendor**. While the second `laravel` is the **The Package**
  - The `laravel/laravel` is called a skeleton project. We are telling composer to build a project based on this skeleton
  - There is difference between `laravel/laravel` and `laravel/framework`
    - `laravel/laravel` => create a template application using the `laravel/framework`
    - `laravel/framework` => create a new project using the `laravel/framework` package. No template files
  - You must be connected to the internet in either case to allow composer to download packages

## Artisan

- Artisan is just a PHP file
- `php artisan <command>` execute specific command inside artisan file

## First Server

- Use `php artisan serve` to start your first local server
  - The default port is 8000
  - If you have XAMPP installed, you will have 2 servers running on your app
    - `htpp://localhost` The apache server
    - `htpp://localhost:8000` Your project

## Request Response Lifecycle

1. User sends a request
2. The entry point for our Laravel application is only `public/index.php` file
   - The `public` directory is the document root. Any files that I need the client (the broweser) to access shall be located here like frontend JS, CSS, Images, etc...
3. The `index.php` file will include `vendor/autoload.php`
   - The `autoload.php` file [and the whole `vendor` directory] is created by composer. Its function is to include the files that have classes when needed automatically instead of including all the files (only need to `use namespaces` and autoload will include the fiels for you)
   - The autoload file [composer] can reach to the packages thanks to `psr-4`: an agreed standard between packages and composer to easily autoload packages. It simply says that the file name must be the same as the class name, and the namespace name must be the same as the directory name
   - Namespaces are containers for classes to avoid conflicts in classes' names
4. The `index.php` file will inclide `bootstrap/app.php` which is the app service container (app class instance)
5. The `app.php` file will include `App/Http/kernel.php` that prepares the environments for the requests
6. The `kernel.php` file will prepare the environment before dispatching the request
   - Loads (includes) configuration files
     - Configuration files are located in `config/` directory
     - Configuration files always return key-value array
   - Detects environments
     - Development (echo errors)
     - Production (no error messages)
   - Imagine kernel like a black box where requests enter and outputs response
7. The `kernel.php` file will load the service providers (SPs)
   - Service Providers (SP) are classes that do specific task with every request (e.g connecting to database)
   - Consider it like a request setup
   - Found in `app/providers/` directory
   - Contains two main methods that are loaded after including the SP class
     - Register: loads first
     - Boot: loads later
   - All registers in all SPs are loaded first, then the boots
   - To control the service providers' order, go to `config/app.php`, then go to `providers` and order the array. If you created a service provider yourself, you need to add it here as well. To disable a SP, simply comment it
   - We have 3 types of SPs
     - Framework SPs
     - App SPs
     - Packages SP
   - Broadcast SP is disabled by default
8. Dispatching the request
   - Determining the controller based on the given route
9. Request hits the router
   - The file which determining the routes with their corrosponding controllers
   - At this stage, we know what's the controllers, but the code inside the controller will not be executed yet
10. Request hits the middleware
   - Middleware is a check layer between the router and the controller, for example. It checks if the user has the permission or authenticated to access a specific controller
11. Request hits the controller
   - At this stage, the controller can call the database and perform logic for execution
12. The controller returns the view to the user as a response

## Service Container

- Service Container is a class that contains variables (instances) of service providers
- This was made to solve the problem of dependency injection
- Service Container is similar to a static class (similar but not static)

## Facades

- Facade is a static class that corrosponds to an object in the Service Container (calling the object methods/properties without instantiation)
- We need to tell Laravel that this Facade Class corrosponds to this object
- For example, when we access `routes/web.php` we find this code
   
   ```php
   Route::get('/', function() {
    return view('weconme');
   });
   ```

   In this code, `Route` is a facade class for the `$router` object. The router object is located in the `App` SP, we can also write the above code using the router object like this

   ```php
   $router = app('router');
   // $router = app()->make('router'); // Another code that works
   $router->get('/', function () {
    return view('welcome');
    });
   ```

   You can create the object yourself with event dispatcher using this code
   
   ```php
   $router = new Router();
   ```
- get method is inside the router object, which is located in `store/vendor/laravel/framework/src/Illuminate/Routing/Router.php`
- If we want to know the corrosponding object to a specific facade class
  1. Go to `/vendor/laravel/framework/src/Illuminate/Support/Facades/<YourFacadeClass>.php`, replace `<YourFacadeClass>` with the actual facade class name
  2. You will find a protected method named `getFacadeAccessor()` that returns the object name in the service container
- There are many facades that we will use in Laravel, examples are `DB` facade that corrosponds the database object
