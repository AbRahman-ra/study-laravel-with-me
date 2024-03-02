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
  - If we put the order info including the products baught, it will be a problem because the order_id(PK) will increment
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

It's called anonymous class, and it's been used since Laravel 9

- Stopped in minuite 27

