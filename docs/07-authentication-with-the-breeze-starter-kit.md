# 07 - Authentication with The Breeze Starter Kit

## Getting Started

- We have multiple packages for authentication
  - Breeze (simple and easy package)
  - Jetstream
  - Fortify
- Both Breeze & Jetstream provide us with the front end files alongside with the backend logic, Jetstream requires more technical knowledge of front end as it deals with Vue.js/Livewire. Fortify provide only backend logic.
- Breeze is a package that implements controllers, routes & views for authentication in my project, it's just a script, that's why se require it as a dev dependency
- Dev dependencies can be removed from the project without affecting it, if I removed Breeze, the created views, routes & controllers will be implemented
- I asked both chatGPT & perplexity.ai why it's a dev dependency and not a normal dependency but they didn't provide a convincing answer, we'll figure it out later

```bash
composer require laravel/breeze --dev
php artisan breeze:install #to install the files
```

- After installation, we'll find there are multiple files added in views, routes & controllers directories. They are responsible for handling the authentication process
- From Laravel 9 onwards, Vite is being used instead of Webpack
- Laravel is now using TailwindCSS instead of Bootstrap

## Blade Componenets

- If we go to `resources/views/auth/login.blade.php` (just an example). We will find the file is having a structure like this

```php
<x-guest-layout>
    <!-- Session Status -->
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <form method="POST" action="{{ route('login') }}">
        @csrf

        <!-- Email Address -->
        <div>
            <x-input-label for="email" :value="__('Email')" />
            <x-text-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" required autofocus autocomplete="username" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <!-- Password -->
        <div class="mt-4">
            <x-input-label for="password" :value="__('Password')" />

            <x-text-input id="password" class="block mt-1 w-full"
                            type="password"
                            name="password"
                            required autocomplete="current-password" />

            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <!-- Remember Me -->
        <div class="block mt-4">
            <label for="remember_me" class="inline-flex items-center">
                <input id="remember_me" type="checkbox" class="rounded dark:bg-gray-900 border-gray-300 dark:border-gray-700 text-indigo-600 shadow-sm focus:ring-indigo-500 dark:focus:ring-indigo-600 dark:focus:ring-offset-gray-800" name="remember">
                <span class="ms-2 text-sm text-gray-600 dark:text-gray-400">{{ __('Remember me') }}</span>
            </label>
        </div>

        <div class="flex items-center justify-end mt-4">
            @if (Route::has('password.request'))
                <a class="underline text-sm text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-gray-100 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 dark:focus:ring-offset-gray-800" href="{{ route('password.request') }}">
                    {{ __('Forgot your password?') }}
                </a>
            @endif

            <x-primary-button class="ms-3">
                {{ __('Log in') }}
            </x-primary-button>
        </div>
    </form>
</x-guest-layout>
```

- What the hell are these `x-.....`?
  - Any tag starts with `x-...` is a blade component
  - The part after the `x-` is the component name (`x-guest-layout` is a component whose name is `guest-layout`)
  - Components can be renamed using camelCase or kebab-case
  - Components can be self-closing or not
  - Components can have attributes with binding
    ```php
    <x-input-label for="password" :value="__('Password')" />
    ```
  - The `:` before the attribute name enables us to write a php expression inside the `""`
  - Components are found inside `components` directory with thw same names in the tags (we can't rename this directory or move it)

## Routes

- If we made 2 routes with the same name, when we call the route with its name, Laravel will call the last one
- We don't need to pass facade class to the views (i.e we can call them directly in the views without passing)
  - When we called `Auth` facade, we didn't mention the namespace and surprisingly it didn't throw an error, how?
    - Introduce aliases, in `config/app.php` go to `aliases` and add `'Auth' => Illuminate\Support\Facade\Auth`
    - Most of the facade classes are aliased in the file, so they are called implicitly to the global namespace, If we remove the `use` statement in the controller file, Intelephense will cry but the code will run without errors
- Assume we have 2 tables for authentication (one for admins & one for users), how can we control that?
  - Let's go to `config/auth.php`
  - You'll first find something like this
    ```php
    return [
    'defaults' => [
        'guard' => 'web',
        'passwords' => 'users',
        ],
    ];
    ```
  Guards are defined below
    ```php
    'guards' => [
        'web' => [
            'driver' => 'session',
            'provider' => 'users',
        ],
    ],
    ```
  Drivers & providers are also defined
    ```php
    'providers' => [
          'users' => [
              'driver' => 'eloquent',
              'model' => App\Models\User::class,
          ],

          // 'users' => [
          //     'driver' => 'database',
          //     'table' => 'users',
          // ],
      ],
    ```
  - What's the difference between database driver & table and eloquent driver and model? 
    - If we `dd($user)` in the controller in both configurations, we will find that the database-table configuration returns a variable of type `GenericUser`, in eloquent-model it will return `User` model type with more information that we can manipulate. Knowing that `$user` is defined as `Auth::user()` in both ways (which is equivalent to `auth()->user()`)
- Assume I am showing the authentication user name, and at the same time I allowing non authenticated users to enter the route, it will result an error when the user is not authenicated because the `auth()->user()` is null, here we have two solutions
  - Conditionally render blade block on authentication
    ```php
    @if(Auth::check)
    <!--content-->
    @endif
    ``` 
    Which is equivalent to a better directive
    ```php
    @auth
    <!--content-->
    @endauth
    ```
  - Use the null coalescing operator `??`
    ```php
    <a href="#" class="d-block">{{ Auth::user()->name?? "guest" }}</a>
    ```
    This means if the user name is null, substitute it with this fallback `"guest"`
- Similar to the `@auth` directive, there is a `@guest` directive
- We can use the `middleware()` inside the route, as well as inside the controller itself. We will define the constructor function to the controller, then we call the middleware method inside it
```php
class DashboardController extends Controller
{
  public function __construct()
  {
    $this->middleware('auth');
  }
}
```
This approach ensures the middleware is applied to all methods inside the controller
- Logout is using `POST` requests instead of `GET` to protect users from CSRFs (Cross-Site Request Forgery)
- We can handle it either with JS or by creating HTML forms
  - Forms will return status code 419 (page expired), because we didn't send the CSRF token with the form
  - To send the token, create a hidden input with name `_token` and value `{{csrf_token()}}`
  - Alternatively, we can use `{{csrf_field()}}` method which does all the same
  - Also, we can use `@csrf` directive which is also equivalent to the both ways above
- In the register & login controllers (`AuthenticatedSessionController`), we will find this code
```php
return redirect()->intended(RouteServiceProvider::HOME);
```
- `intended` means the endpoint the user was requesting before authentication
- Since the redirect helper is a facade cass, we can use it as well
    ```php
    return Redirect::intended('<defaultRoute>');
    ```
- To allow email verification, in the user model we implement `MustVerifyEmail` interface, then we should set up our mail server configurations from `.env` file
- We can set the mail mailer from `smtp` to `log` and then we go to `storage/logs/laravel.log` and we will find the confirmation emssage
- We can also apply `verify` middleware that checks that the user has verified his email before entering to a specific endpoint
  - By checking the `email_verified_at` field in the `users` table that's not null