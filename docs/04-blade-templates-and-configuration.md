# 04 - Blade Templates and Configuration

## General Info

- Breadcrumbs are text or visual that demonstrates the user position in the flow
- For example, some websites write at the top of a post page `Author > Posts > Post Title`, or in our dashboard `Home > Starter Page`, all of these texts are breadcrumbs

## Blade - Continued

- If we want to define a section in the layout and show it at the same time, we use `@show` directive instead of `@endsection`. the show directive is exactly equivalent to the following code
  ```php
  @section('content')
  Some content
  @endsection('content')
  @yield('content')
  ```
- Think of the layout as a parent for the view, if there is a section-show in the layout and a section in the view with the same name, the view section will overwrite the one in the layout
  - Like a parent class has a method, and a child class defined the same method, the method will be overridden
  - If you want to use the parent class method, you would call it with `parent::methodName()` inside the child class. Similarly, if you would like to view both contents without overriding, you call `@parent` in the view (child) file section
- Suppose the following case
  - You have layout file and a view file
  - In the view page, we need to show a breadcrumb `<LayoutPage>/<ViewPage>`
  - To do this, let's first define the desired HTML
  ```html
  <ol>
    <li>Layout Page</li>
    <li>View Page</li>
  </ol>
  ```
  - Now, let's split the content into layout file and view file, starting with the view file
  ```php
  @section('breadcrumb')
  <li>View Page</li>
  @endsection
  ```
  Similarly, the layout file
  ```php
  @section('breadcrumb')
  <ol>
    <li>Layout Page</li>
    @yield('breadcrumb')
  </ol>
  @show
  ```
  Now, we need to tell the layout breadcrumb that we are calling its child (view) in the `@yield` and not itself. We need to tell teh child that your parent is calling you so we add `@parent` to the child file
  ```php
  @section('breadcrumb')
  @parent
  <li>View Page</li>
  @endsection
  ```
  Now, we get a correct view
- `@yield` can have a default value to be shown if the section was not found in the view
- If we have a section that's not needed in all pages (e.g scripts and styles loading), and maybe it got repeated with different content. We can't use a section in our case since it will override the old sections, that's why we use stacks
  - In the layout file, we define stack by the `@stack('stackName')` directive. Then, in the view file, we push code to the stack between `@push('stackName')` & `@endpush`, no matter how many times we push, the content will not be overridden but appended
- Another way to add content modularly to our view is to use partials (i.e creating components as files then include it), we can include partials using `@include` directive, a good question now arises: when to use `@section` and when to use `@incldue`? The answer is that `@include` is good with the static parts of the webside such as headers, footers, nav bars & aside menus, usually called in the `layout` files. While `@section` is ususally used with dynamic parts that change from view to another.
- We give the `@include()` directive an input of the path relative to `views` directory. No matter where are you calling this directive, you should always give the path relative to the main `view` directory, you can also pass an array of data as a second argument that can be used in the partial file

## Configurations

- The `.env` file has no name in purpose to be hidden in linux servers as a type of security
- In `.env` file, if we have values that have spaces, we must put it in a double quotation, if it's one word (i.e no white spaces), we have the option to include it or not
- When deploying your app from local to production, don't forget to change the `APP_ENV` into `production` and `APP_DEBUG` into `false`. If errors happened it will be stored to a log file that the developers can access but the user will see an error message without any details
- `APP_KEY` is a base64 key string that's used by laravel to encrypt data
- It's better to specify the port number in the `APP_URL` environment variable
- `ASSET_URL` is not defined in the `.env` file. It's used when our assets are in another server, it's called in `config/app.php`
- The best practice is to use `env()` helper only inside the configuration files
  - When we deploy our website, we will use `php artisan config:cache` to create congfig cache, that makes the website faster. Thus, any value that's read from the `.env` file outside the config files will be null.
  - To read `.env` values outside of the config files, read the config file key that's pointing to the `.env` file, Laravel reads the `.env` file for the first time only, then no matter how many changes you do, it will not read the file unless you delete te cache
  - Config cache is found in `bootstrap/cache/config.php` which is an array of all config files with the values directly without any `env()` directly
  - We can change the content of the cache file and get what we want
  - Instead of reading `env('APP_NAME')`, just write `config('app.name')`, because in `config/app.php` there is `'name' => env('APP_NAME', 'Laravel'),`
    - `config('app')` will return the whole array, this means we can write `config('app')['name']` which is technically correct but not common


## Base64 Encoding

- Base64 encoding is neither hashing or description, it's just a way to represent strings (like binary & decimal numbering systems)
- The base64 encoded string can be a character (letter), a number, a `+` and a `/` only
- To encode a string from utf8 (normal string) to base64, follow these steps
  1. Find the [ASCII value](https://www.ascii-code.com/) for each letter in the string
  2. Convert the ASCII value into an 8-bit  binary number
  3. Concatenate all the values together
  4. Seperate the values to be 6-bit intead of 8 bit, if the last value contains less than 6 bits, add zeros to make it 6
  5. Convert the separated values into new decimal numbers
  6. From the [base64 table](https://base64.guru/learn/base64-characters), locate the caracter corrosponding to the decimal value
  7. If the new string length is not divisable by 4, add `=` until it becomes visible by 4

```ts
let decoded = "Hello World";
/*
Character => ASCII Value => Binary Value
H => 72 => 01001000
e => 101 => 01100101
l => 108 => 01101100
l => 108 => 01101100
o => 111 => 01101111
Space => 32 => 00100000
W => 87 => 01010111
o => 111 => 01101111
r => 114 => 01110010
l => 108 => 01101100
d => 100 => 01100100

Concatenate
*/

let binary = "01001000 01100101 01101100 01101100 01101111 00100000 01010111 01101111 01110010 01101100 01100100";

// Divide into 6 bits instad of 8
binary = "010010 000110 010101 101100 011011 000110 111100 100000 010101 110110 111101 110010 011011 000110 0100";

// Add padding (extra zeros) to the last 6-bits
binary = "010010 000110 010101 101100 011011 000110 111100 100000 010101 110110 111101 110010 011011 000110 010000";

// Convert them to decimal
let decimal = "18 6 21 44 27 6 60 32 21 54 61 50 27 6 16";

// Locate characters on Base64 Table
let base64encoded = "SGVsbG8gV29ybGQ";

// Since the length (15) is not divisable by 4, we add padding (extra `=`)
base64encoded = "SGVsbG8gV29ybGQ="; //SGVsbG8gV29ybGQ
```

## Database Configuration

- `DB_CONNECTION` in `.env` file is just a name for the connection and not necessarily to be the same as the database type, we can name it as we wish but with proper configuration (we need to change/add connection name in `config/database.php` file)
- Laravel supports 5 database drivers only
  - MySQL
  - PostegreSQL
  - SQLite
  - Microsoft SQL Server
  - Redis
  - If we need to use another database (e.g Oracle, MongoDB, etc..) or connect to more than 1 database at the same time, we need to install the corrosponding package(s) and add new connections configurations and environment variables
- When creating a new database, there is a collation which is a sub charset (every charset has multiple collations). The best practice to support arabic and english letters is to use utf8/utf8mb4 & utf8mb4_unicode_ci/utf8mb4_general_ci/utf8mb4_unicode_520_ci
  - `ci` means case insensitive, this is useful for querying data