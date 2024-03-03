# 06 - Database Seeders

## Motivation

- In testing, we might need dummy data to simulate real life testing
- Seeders & Factories helps us injecting dummy data to database, we can use two types of injectors
  - Seeders: inject small amount of data with values we choose (like the default user account)
  - Factories: inject large amount of data with random values

## Seeders

- To create a new seeder, run `php artisan:make seeder UserSeeder`
- Seeder file will contain only one method `run()` that's responsible for injecting values into database
- We can use the model in the seeder file to create new record

## Passwords

- How does Google knows our old passwords?
  - "This password has been changed since 4 months"
  - They store logs of old password hashes
- We hash the passwords not only to secure our system aginst attacks, but to prevent our employees and database admins from manipulating our data
- The model is used to call the database and run queries. However, it's not necessary that every table must have a corrosponding model in the app. Also, we can call specific tables using the `DB` facade and its query builder.
- The `DB` facade doesn't fill the timestamps

```php
User::create([
  'name' => 'rashed',
  'email' => 'rashed@gmail.com',
  'password' => Hash::make('rashed'),
  'phone' => '+973000000001'
]);

DB::table('users')->insert([
  'name' => 'admin',
  'email' => 'admin@gmail.com',
  'password' => Hash::make('admin'),
]);
```

- To execute the seeder file, we must use `$this->call()` method in the `DatabaseSeeder` file, this method accepts the seeder file class name, after that, we run this command in the terminal `php artisan db:seed`