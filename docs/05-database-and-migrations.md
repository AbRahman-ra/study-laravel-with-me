# 05 - Database and Migrations

## Getting Started with Database

- The instructor chose MySQL. However, I personally prefer PostgreSQL, there will be no big difference between the two of them
- Some columns (like `id`) are working on CoC (Convention over Configuration), in other words, if we don't configure them (i.e changed their name or type), Laravel will handle many things to us with them
  - This doesn't mean it's recommended to keep them to the default configuration or it's not recommended to change them. It's up to the developer to choose what suits their solution best
- In MySQL, any auto incrementing field is a primary key by default
- Slugs are used in the links to enhance SEO compatability
- Slugs must be unique

## Database

- Project: Multi Vendor Store
- Assume we have categories table for the products, how can we handle nesting?
  - If we have a category (e.g clothes), we can have categories inside that category (men, women), and even inside the subcategory we can have another sub subcategory (teens, kids, adults, plus-size). So, instead of creating `categories`, `sub_categories`, `sub_sub_categories`, ...etc. We can create one table `categories` and we add a nullable column `parent_id` which is a forign key referring to the super (parent) category id on the same table
- We will assume a one-to-many relationship between the category and the product (category has many products, product has one category)
- Why do we use the `id`s of other tables as foreign keys? Why don't we make the name unique and we make it directly the primary key and foreign key in other tables instead of id?
  - Because if the user changed the name, we will have to go through all the tables and change it, but the id is inaccessable by the user so it's safer to link with it
- Let's create an `orders` table
  - How can we document the products that was ordered?
  - If we put the order info including the products baught, it will be a problem because the `order_id (PK)` will increment
  - We can't put the order details as an array/JSON in the table because it will be harder to query
  - The optimized solution is to create 2 tables `orders` & `orders_items`

## Database Design

- Helpers:
  - PK: Primary Key
  - FK: Foreign Key
  - UQ: Unique
  - `...`: Other fields that will be mentioned later
- Tables:
  - `products` (id (PK), name, slug (UQ), description, category_id (FK), Stores (FK), user_id (FK), ...)
  - `categories` (id (PK), name, slug (UQ))
  - `stores` (id (PK), name)
  - `orders` (id (PK), transaction_number, store_id (FK), user_id (FK), status, ...)
  - `orders_items` (order_id (FK), product_id (FK), qty, ...)
  - `cart`

## Creating Migrations

- Migrations are the DDL (Data Definition Language) that creates, updates or deletes tables from the databse
  - DDL is responsible for the database tables, attributes and schema, not the records
- It's recommended to name your create migrations `create_<tableName>_table`. Laravel will check if the migration name starts with `create` & ends with `table` it will automatically consider what's in the middle as the table name
- It's recommended to name the table with plural names (`stores`, `users`, `tokens`, etc...)
- The migration file name is `2024_03_02_204004_create_stores_table`
  - `2024_03_02` => date created
  - `204004` => `20:40:04` => time created
    - We can edit the timezone settings in `config/app.php` by going to `timezone` key and either make it `UTC+3` or `Asia/Bahrain`
- In the migration file, you will see the migration file is a class without name!!

```php

return new class extends Migration
{

}
```

It's called anonymous class, and it's been used since Laravel 9, this class must be instantiated in the return (has no name so we can't instantiate it elsewhere). This class has two methods (`up()` for running the migrations and `down()` for reversing the migrations)

- We have 5 types of integers in MySQL
  - `TINYINT`: 1 byte (8 bit) => 2^8 = 256 => unsigned [0 : 2^8-1] or signed [-2^7 : 2^7-1]
  - `SMALLINT`: 2 bytes (16 bit) => 2^16 => unsigned [0 : 2^16-1] or signed [-2^15 : 2^15-1]
  - `MEDIUMINT`: 3 bytes (24 bit) => 2^24 => unsigned [0 : 2^24-1] or signed [-2^23 : 2^23-1]
  - `INT`: 4 bytes (32 bit) => 2^32 => unsigned [0 : 2^32-1] or signed [-2^31 : 2^31-1]
  - `BIGINT`: 8-bytes (64 bits): 2^64 => unsigned [0 : 2^64-1] or signed [-2^63 : 2^63-1]
  - Have a look at this [Stack Overflow Question](https://stackoverflow.com/questions/2991405/what-is-the-difference-between-tinyint-smallint-mediumint-bigint-and-int-in-m)

```php
$table->id();
```

Is equivalent to

```sql
id BIGINT UNSIGNED PRIMARY KEY AUTO INCREMENT
```

- We can rewrite it in different methods in Laravel

```php
$table->bigInteger('id')->unsigned()->autoIncrement()->primary();
```

- Since the `autoIncrement` is a primary key by default, we can remove the last method. Also, we can merge the `unsigned()` with the `bigInteger()` using `unsignedBigInteger()` method

```php
$table->unsignedBigInteger('id')->autoIncrement();
```

- We can also merge the `unsignedBigInteger()` with the `autoIncrement()` using `bigIncrements()` method

```php
$table->bigIncrements('id');
```

- Some databases like SQL Server supports the auto creation of `UUID`s
- The `iumestamps()` methods creates two fields (`created_at` and `updated_at`)
- The second argument in the `string()` method is the maximum length, knowing that `string` is equiavalent to `VARCHAR()` in SQL
- There is also another data type in SQL `TEXT` with the method `text()`. So what's the difference between `text` & `VARCHAR`?
  - `VARCHAR` is used when we want to put a constraint on the input size, while `text` can support up to 65536 (2^16) characters  
- We can use access midifies to add attributes to our fields
- The first access modifier is `unique()`
- What's the difference between unique and primary key
  - Unique can be null, primary key can't
  - Unique usually doesn't increment automatically
- We can add a relation between two tables using a unique key and not necessarily primary key
- By default, the fields are not null unless we state otherwise using `nullable()` method
- We didn't add `unique` access modifier to the images fields because the name of the image may repeat

```php
$table->string('logo_image')->nullable();
$table->string('cover_image')->nullable();
```

- The `softDeletes()` method adds a nullable column `deleted_at` that stores the date of deletion, usually used with amything related to transactions
- When I write `php artisan migrate`, Laravel goes to the migrations folder and runs the `up()` method in all files
- If I want to update the migration, we have multiple options
  - Add the update into a separate migration file
  - Update the migration file itself and re-run the migrations
    - Rollbacking the migration may cause data loss
    - We can do this using `php artisan migrate:rollback`, it will undo the last migration only
    - On production, it's prohibited to use rollback
- We can create the relation between the `parent_id` and the `id` column using either way below

```php
// Method 1
$table->foreignId('parent_id')->references('id')->on('categories')->nullable();

// Method 2
$table->foreignId('parent_id')->constrained('categories','id')->nullable(); // Same
```

- Understanding `ON DELETE` events on foreign keys
- Assume you have 3 fields

| category_id | parent_id | name |
| --- | --- | --- |
| 1 | `NULL` | clothes |
| 2 | 1 | for her |
| 3 | 2 | kids |

- `ON DELETE RESTRICT` => `restrictOnDelte()` means that we can't delete any record being referred to, so if we deleted the record with `categry_id` = 1, it will prevent us because it's being referred to by record with `category_id` = 2 (the same with `category_id` = 2 & `category_id` = 3)
- `ON DELETE SET NULL` => `nullOnDelete()` means that if we delete the record being referred to, the reference will become `NULL`, so if we deleted record with `category_id` = 1. The `parent_id` in the second record will be `NULL` because it's referring to no longer existing record
- `ON DELETE CASCADE` => `cascadeOnDelete()` means that if we delete any record being referred to, all records referring to it will be deleted as well. Thus, if we deleted the first record, the second record will be deleted as well because it's referring to it, the third record will be also deleted because it was referring to the second record, and so on...
- The default selection in Laravel is `restrictOnDelete()`
- `nullOnDelete()` must be in a `nullable()` field
- Column properties must be before the relation properties, let's elaborate

```php
$table->foreignId('parent_id')
      ->references('id')
      ->on('categories')
      ->nullOnDelete();
      ->nullable()
```

In the above snippet, the `nullable()` will not work and it will result an error, because the `nullOnDelete()` method returns a relation instance not a model instance, same with `references()` & `on()`

- We can track our migrations using `php artisan migrate:status`, we will get a table with migration name, batch and status (`Ran` - `Pending`). The batch is the migration sequence (1: first migration, 2: second migration, etc...), the rollback is simply going to the first to last migration
- To rollback more than one step, we can write `php artisan migrate:rollback --step=x` where `x` is the number of steps we want to rollback
- To rollback all migrations, we write `php artisan migrate:reset`
- `php artisan migrate:refresh` is equivalent to running `php artisan migrate:reset` & `php artisan migrate` together
- Note that `refresh`, `rollback` & `reset` functions are calling the `down()` method. Unlike `php artisan migrate:fresh` which is dropping the tables no matter what's inside the `down()` method then migrates the files again
- If we got an error that the foreign key is long, that's because the unique fields creates indexes in the database and the maximum index length is by default is 191 characters, we can solve this in many ways
  - Specify the table engine to be `InnoDB`
  - Encdoe the database using `utf8` instead of `utf8mb4`
  - In `config/databse.php` go for keys `charset`, `collation` & `engine` inside the `connections`

## Making Models

- Model is used to query data
- Model is a class, so its name should be singular & written in PascalCase
- If I want to create a corrosponding migration file at the same time I am creating a model, we use the `-m` flag, to become `php artisan make:model -m Product`
- The model class is using `HasFactory` trait
- If I didn't follow Laravel standards, I will configure my table in the model file
  - For example, if I created a table named `stores_info` instead of `stores`. I must configure and point out that this model is corrosponding to this table in the model file
    ```php
    protected $table = 'stores_info';
    ```
  - Similarly, if the table is in another database connection (not the default one), I will define this snippet
    ```php
    protected $connection = '<connectionNameFromConfig/DatabaseFile>';
    ```
  - Laravel assumes the primary key is always a field named `id`. If it's something else, we can define it as well
    ```php
    protected $primaryKey = 'user_id';
    ```
  - If the primary key is something like `uuid` which is not auto-incrementing, we can configure that too, `incrementing` & `timestamps` are the only public properties, all others are protected
    ```php
    public $incrementing = false;
    ```
  - If I don't need timestamps anymore, deleting them from the migrations is not enough, because this will result an error as Laravel will try to inject the columns even if the fields are deleted. So, we prevent laravel from injecting them by disabling the `timestamps` property
    ```php
    public $timestamps = false;
    ```
  - If we changed the names of `created_at` & `updated_at` fields, we shall add them but as constants to the model
    ```php
    const CREATED_AT = 'created_on';
    const UPDATED_AT = 'updated_on';
    ```
  - Many other properties can be discovered from the `Model` class