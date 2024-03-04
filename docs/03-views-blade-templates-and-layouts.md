# 02 - Views, Blade Templates and Layouts

## Controllers

- The file `Controller.php` located inside `App/Http/Controllers` is the main controller class that other controllers extend
- If we looked into it, we will found something like
    ```php
    class Controller extends BaseController
    {
        use AuthorizesRequests, ValidatesRequests;
    }
    ```
- `AuthorizesRequests` and `ValidatesRequests` are traits
- Methods inside controller are called actions
- Actions must return response, response can have multiple shapes
  - View
  - JSON Data
  - File(s) (like downloads)
  - Redirect
- In laravel 8+, we can pass an array of the class and action names for the route (before Laravel 8, this was allowed only in static methods)
    ```php
    // Laravel 8 & above
    Route::get('/dashboard', [DashboardController::class, 'index']);

    // Before Laravel 8 (still working but not common)
    Route::get('/dashboard', 'App\Http\Controllers\DashboardController@index');
    ```
- Assume you wrote the controller name without the name space using the old approach
    ```php
    Route::get('/dashboard', 'DashboardController@index');
    ```
- You will get an error `class [DashboardController] doesn't exist`. Note that the error is mentioning class `[DashboardController]` and not `App\Http\Controllers\DashboardController`, this means the application is looking for the class in the global namespace
- If we want to write only the controller name without the namespace:
  1. Go to `App/Providers/RouteServiceProvider`
  2. Create a `protected $namespace = 'App\Http\Controllers';`, 
  3. In the `$this->routes(function(){})`, add the namespace to `Route::middleware('web')` by adding `->namespace($this->namespace)`
- this means that any controller name written in the routes will be considered under this namespace by default
- It's always a best practice to stick to the defaults

## Request Methods

- Assume you defined the following route
    ```php
    Route::post('/dashboard', [DashboardController::class, 'index']);
    ```
    And you tried to access it using your browser (`GET`), you will get an error with a status code 405 `Method Not Allowed`
- If you want to allow any method, use `Route::any`
- If you want to allow specific methods, use `Route::match(['get', 'post'], '/endpoint', [ActionController::class, 'actionName'])`
- `any` & `match` are not the best practice

## Blade Introduction

- Blade is a template engine that enables us to write easy code without too much PHP
- Assume I changed the above route to something like this
  ```php
  Route::get('/dashboard/index', [DashboardController::class, 'index']);
  ``` 
  Now, the view will be incomplete, let's analyze why.
  
  Consider this HTML View

  ```html
  <img src="header.jpg">
  ```

  Now, since we changed the endpoint from `/dashboard` to `/dashboard/index`. The server thinks that we have entered the `dashboard` directory inside the `public` folder, which doesn't even exist!

  Thus, when calling an image with path `header.png`, the relative path will count the image from the `dashboard` directory and will not find it, so it will not be loaded

- To solve this issue, we can use PHP function `asset` and give it the path from the public folder, and it will be updated dynamically
    ```php
   <img src="<?= asset('header.jpg') ?>"> 
    ```
- To be able to write blade syntax, we must use `.blade.php` extension. No need to change anything in the `view()` helper, also, we can write PHP normally in blade files
- `<?php echo ... ?>` can be shorthanded to `<?= ... ?>`. In blade we can even shorthand it more with `{{ ... }}`
- We can pass variables in blade using the `view()` helper
    ```php
    Route::get('/dashboard', function() {
        $user = 'Rashed';
        $id = 2376845891208;
        return view('dashboard', compact('user', 'id'));
    });
    ```
    - `compact` is a PHP function that returns an array of variables, containing the variable name as a key, and its value as a value. It accepts variable names as strings. It's very similar to ES6 object construction in JavaScript/TypeScript
    ```ts
    let user = "Rashed";
    let id = 8093456246235;
    
    type User = {
        user: string,
        id: number,
    };

    let userData:User = {user, id};
    ``` 
    It's a better approach instead of defining the array yourself
- You can also pass data to the view using the `with` method
    ```php
    Route::get('/dashboard', function() {
        $user = 'Rashed';
        $id = 2376845891208;
        return view('dashboard')->with(compact('user', 'id'));
    });
    ```
- Most of the helpers have facade classes, for example, `view` helper is a facade class
    ```php
    use Illuminate\Support\Facades\View;
    class DashboardController extends Controller
    {
        public function index()
        {
            return View::make('dashboard');
        }
    }
    ```
- We can return the view from the response object or responce facade
    ```php
    use Illuminate\Support\Facades\View;
    use Illuminate\Support\Facades\Response;
    class DashboardController extends Controller
    {
        public function index()
        {
            // Using response helper
            return response()->view('dashboard');

            // Using Response facade
            return Response::view('dashboard');
        }
    }
    ```
- There is no difference between multiple syntaxes that do the same thing. The best practice is to use the `view()` helper

## Blade Layouts

- Layout is just a general structure for the page without content
- To tell blade to view/implement our content here, we use `@yield` directive
    ```php
    <html>
    @yield('content') <!--content is implemented here-->
    </html>
    ```
- In the meantime, we still returning the view of the content only (not the layout)
    ```php
    return view('dashboard.index');
    ```
    The `.` is used to access the folder contents, so in this example we access the `dashboard` directory and getting the `index.blade.php` file. The `/` instead of `.` is correct but it's not common with the syntax of returning views
- Similarly, we need to tell the content file that it belongs to a certain layout. Thus, we use the `@extends` directive
     ```php
    @extends('layouts.dashboard') <!--content is implemented here-->
    <section>
    Content
    </section>
    ```
- If we loaded the view now, we will find that the content is not in its correct place, that's because we mentioned that we want to `@yield('content')` while in the content file we didn't specify this `content` section to be yielded. So, we need to include the beginning and the end of our content using `@section('content')` and `@endsection` directives
- If te section has very few code
    ```php
    @section('title')
    Home Page
    @endsection
    ```
    We can define it in one liner code `@section('title', 'Home Page')` instead of breaking into multiple lines
- View file can have multiple sections, and layout file can have multiple yields