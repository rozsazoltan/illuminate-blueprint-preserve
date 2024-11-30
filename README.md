**Rose Production**

# illuminate-blueprint-preserve

> [!NOTE]
> I explained the working principle of the `Blueprint::column` macro included in the package in a [StackOverflow question](https://stackoverflow.com/a/79239558/15167500). The main goal was to carry forward the logic retained up until the older Laravel 10.

### Getting Started

```
composer require rozsazoltan/illuminate-blueprint-preserve
```

### Usage

I will demonstrate the simplification provided by the package based on the modification mentioned in the Laravel documentation.

* [Modifying Columns](https://laravel.com/docs/11.x/upgrade#modifying-columns) - Laravel 11 Upgrade Guide

> For example, imagine you have a migration that creates a votes column
> with the unsigned, default, and comment attributes:
> 
> ```php
> Schema::create('users', function (Blueprint $table) {
>     $table->integer('votes')->unsigned()->default(1)->comment('The vote count');
> });
> ```
> 
> Later, you write a migration that changes the column to be nullable as
> well:
> 
> ```php
> Schema::table('users', function (Blueprint $table) {
>     $table->integer('votes')->nullable()->change();
> });
> ```
> 
> In Laravel 10, this migration would retain the unsigned, default, and
> comment attributes on the column. However, in Laravel 11, the
> migration must now also include all of the attributes that were
> previously defined on the column. Otherwise, they will be dropped:
> 
> ```php
> Schema::table('users', function (Blueprint $table) {
>     $table->integer('votes')
>         ->unsigned()
>         ->default(1)
>         ->comment('The vote count')
>         ->nullable()
>         ->change();
> });
> ```

However, with my `column` macro, if I only want to modify the `nullable` on an existing column and keep all other attributes intact, the modification would look like this:

```php
Schema::table('users', function (Blueprint $table) {
    $table->column('votes')->nullable();
});
```

#### Informations

- There is no need to call `change()` separately when using the column macro.
- The set values can be overridden and will take effect when the migration is executed.

#### Change `type`

```php
Schema::table('users', function (Blueprint $table) {
    $table->column('votes')->type('float');
});
```

#### Reverting `nullable`

```php
Schema::table('users', function (Blueprint $table) {
    $table->column('votes')->nullable(false);
});
```

#### Reverting auto increment

```php
Schema::table('users', function (Blueprint $table) {
    $table->column('votes')->autoIncrement(false);
});
```

#### Other

All other functions that already work, such as `default`, `comment`, etc., continue to function as expected.

## Support

I greatly appreciate your support for my efforts, whether through contributions, bug reports, sharing, starring, an upvote on Stack Overflow, or anything else that comes to mind.

## License

All rights reserved as specified in the [`LICENSE`](./LICENSE) file! This project can be considered reusable, copyable, and/or distributable, provided that the original public repository link is included in the source code and made visible to those who use the product that incorporates this source code/package.
