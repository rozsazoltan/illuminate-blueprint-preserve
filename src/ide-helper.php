<?php

/**
 * @method \Illuminate\Database\Schema\ColumnDefinition column(string $column) Set column properties for change (by illuminate-blueprint-preserve package)
 */
class Blueprint
{
    //
}

if (! class_exists(Blueprint::class)) {
    class_alias(Blueprint::class, \Illuminate\Database\Schema\Blueprint::class);
}


/**
 * @method $this autoIncrement(bool $value = true) Set INTEGER columns as auto-increment (primary key) or NON-auto-increment (by illuminate-blueprint-preserve package)
 * @method $this unsigned(bool $value = true) Set the INTEGER column as UNSIGNED (MySQL) or NON-UNSIGNED (by illuminate-blueprint-preserve package)
 */
class ColumnDefinition
{
    //
}

if (! class_exists(ColumnDefinition::class)) {
    class_alias(ColumnDefinition::class, \Illuminate\Database\Schema\ColumnDefinition::class);
}

