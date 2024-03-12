# 08 - CRUD: Resource Controllers and Routes

## Creating Controllers

- Resource Controllers are controllers that came with 7 default methods
  - `index()`
  - `create()`
  - `store()`
  - `show()`
  - `edit()`
  - `update()`
  - `destroy()`
- Like Breeze, we can create ourcontroller in a sub namespace
  - Write `php artisan make:controller Dashboard\CategoriesController`
  - The new controller will have the namespace `App\Http\Controllers\Dashboard`
  - We can make the controller a respource controller by adding the flag `-r` to the end of the command
- It's preferred to name the views folders and subfolders with the namespaces/sub namespaces names

## Querying Data

- When we call a model to return some data

```php
  $category = Category::where('id', 1)->first();
```

- The return is a collection class instance, not an array. It's implementing ArrayAccess interface. This interface enables us to write the syntax of an array on the object. Let's track it
  - The `where` function returns `Illuminate\Database\Eloquent\Collection`
  - The `Collection` Class extends `BaseCollection`
  - The `Illuminate\Support\Collection` implements `ArrayAccess` Interface
- We can use some array methods with the returning object
  - `$category[0];` is a valid syntax

## Routing

- Instead of defining 7 routes, we can use the `resource` method that will define everything for me as long as it's on the default naming

```php
Route::resource(?$prefix, ControllerClass::class)
```

- To see the routes as list, write this terminal command

```bash
php artisan route:list
```

- The output will be something like this

```bash

  GET|HEAD        / ..................................................................................................
  POST            _ignition/execute-solution ignition.executeSolution › Spatie\LaravelIgnition › ExecuteSolutionContr…
  GET|HEAD        _ignition/health-check ....... ignition.healthCheck › Spatie\LaravelIgnition › HealthCheckController
  POST            _ignition/update-config .... ignition.updateConfig › Spatie\LaravelIgnition › UpdateConfigController
  GET|HEAD        api/user ...........................................................................................
  GET|HEAD        confirm-password ........................ password.confirm › Auth\ConfirmablePasswordController@show
  POST            confirm-password .......................................... Auth\ConfirmablePasswordController@store
  GET|HEAD        dashboard .................................................... dashboard › DashboardController@index
  GET|HEAD        dashboard/categories ........................ categories.index › DashboardCategoriesController@index
  POST            dashboard/categories ........................ categories.store › DashboardCategoriesController@store
  GET|HEAD        dashboard/categories/create ............... categories.create › DashboardCategoriesController@create
  GET|HEAD        dashboard/categories/{category} ............... categories.show › DashboardCategoriesController@show
  PUT|PATCH       dashboard/categories/{category} ........... categories.update › DashboardCategoriesController@update
  DELETE          dashboard/categories/{category} ......... categories.destroy › DashboardCategoriesController@destroy
  GET|HEAD        dashboard/categories/{category}/edit .......... categories.edit › DashboardCategoriesController@edit
  POST            email/verification-notification verification.send › Auth\EmailVerificationNotificationController@st…
  GET|HEAD        forgot-password ......................... password.request › Auth\PasswordResetLinkController@create
  POST            forgot-password ............................ password.email › Auth\PasswordResetLinkController@store
  GET|HEAD        login ........................................... login › Auth\AuthenticatedSessionController@create
  POST            login .................................................... Auth\AuthenticatedSessionController@store
  POST            logout ........................................ logout › Auth\AuthenticatedSessionController@destroy
  PUT             password .......................................... password.update › Auth\PasswordController@update
  GET|HEAD        profile ...................................................... profile.edit › ProfileController@edit
  PATCH           profile .................................................. profile.update › ProfileController@update
  DELETE          profile ................................................ profile.destroy › ProfileController@destroy
  GET|HEAD        register ........................................... register › Auth\RegisteredUserController@create
  POST            register ....................................................... Auth\RegisteredUserController@store
  POST            reset-password ................................... password.store › Auth\NewPasswordController@store
  GET|HEAD        reset-password/{token} .......................... password.reset › Auth\NewPasswordController@create
  GET|HEAD        sanctum/csrf-cookie .............. sanctum.csrf-cookie › Laravel\Sanctum › CsrfCookieController@show
  GET|HEAD        verify-email .......................... verification.notice › Auth\EmailVerificationPromptController
  GET|HEAD        verify-email/{id}/{hash} .......................... verification.verify › Auth\VerifyEmailController

                                                                                                   Showing [32] routes
```

## Views

- Introduce Emmet Abbreviation
  - To create a table fastly, we write something like this

```html
table.table>thead>tr>th*4
```

What does this mean?

- Create a table `table`
- The table class is table `.table`
- The table has a header `>thead`
- The table header has 1 rows `>tr`
- The table header row has 4 cells `>th*4`

- To send forms in `PUT/PATCH/DELETE` methods, we use form method spoofing, we can do this in 2 ways

  - No Blade: create a hidden input with name = `_method` and value of the method value
    - `<input type="hidden" name="_method" value="delete" />`
  - Blade: use the `@method` directive
    - `@method('DELETE')`

- In PHP, Object is always a truthy value (i.e. both of the below snippets are equivalent)

```php
// Snippet 1
if ({}) {
  // Do Something
}

// Snippet 2
if (true) {
  // Do Something
}
```

- Thus, we can't use `if($categories)` to check if the categories is empty or not, we better use `if($categories->count())`, similar to JS `array.length` instead of `array`
- In blade, we can check the object truthiness by nesting the `@foreach` inside a condition, or a better approach, using the `@forelse` directive, which is a `@foreach` with `@empty` for a fallback if the collection/array/object is empty
- In HTML: `<option value="" selected disabled>Select a parent category</option>`, What's sent to database is the value. The `Select a parent category` is just for the user. It will be sent to the server only if there is no value to the option. If we want to send a null to the server, we just make the value equals an empty string as shown

## Submitting The Form

- Now, in the `store()` method we willc reate our actions, to access submitted parameters we can use `$request->input("param")` which means "Give me param that's sent in the request no matter how it's sent"
- There is more accurate approach `$request->post("param")` which means "Give me param that was sent using the post request body", how can that be useful?
  - Assume you have a field name and a query parameter with the same name. How can you differentiate between them? Here, the `$request->post()` & `$request->query()` will be effective
- We have 6 methods to get request information
  - `$request->input('<param>')`
  - `$request->post('<param>')`
  - `$request->query('<param>')`
  - `$request->get('<param>')` - similar to `input()`
    - [This Answer on Stack Overflow](https://stackoverflow.com/questions/30186169/laravel-request-input-or-get) explains the few differences between both of them
  - `$request-><param>`
  - `$request['<param>']` because the request class is implementing array access interface
- Additional request manipulation
  - `$request->all()` returns **an array** of the request object
  - `$request->only([])`
  - `$request->except([])`
- If the form names are the same as the columns names, go ahead with this one liner

```php
$category = Category::create($request->all());
```

- After form submission, we should do PRG (Post Redirect Get), we shouldn't keep a post request as it is in the controller, we must return a redirect or a get method
- The above one liner will show us an error regarding the mass assignment
  - Mass assignment means that there are excess form fields that are submitted, so in order to control the form we configure the `$fillable` array in the model
- The opposite to the fillable array is teh `$guarded` array, the fields in this array can't be inserted to the database (like id)
- We usually define either fillable or guarded, but not both of them
- Fillable is better and more secure
- We call fillable a whitelist, while guarded a black list
- Empty guarded array means all fields are fillable

## Updating Records

- Consider this update button

```html
<a href="{{ route('categories.edit', $category->id) }}" class="btn btn-info"
  >Edit</a
>
```

- The `route('categories.edit')` is pointing to this endpoint `/dashboard/categories/{category}/edit` where `{category}` is a request parameter representing category id
- In these routes inside blade files, we must pass an input as a second argument
  - We can use a normal array following the same order of the parameters in the endpoint.
  - Or we define keys and values with our order but the key must equal the parameter name
- We may want to send a flash message (success or failure message) after the post request, a flash message is a message that is stored and deleted in the same session after it's been read
- One of the simplist methods is to use the `with($key, $value)` helper in the redirect and read it either in the redirected controller or in the view, let's show it in the view using the if condition

```php
@if(session()->has($key))
  <div class="alert alert-success">
    {{ session()->get($key) }}
  </div>
@endif
```

- Laravel will delete the flash message automatically
