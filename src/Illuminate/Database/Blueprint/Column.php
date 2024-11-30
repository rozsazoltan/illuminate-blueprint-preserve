<?php

namespace Rose\BlueprintPreserve\Database\Schema\Blueprint;

use Rose\BlueprintPreserve\Database\Schema\Blueprint\Helpers\ColumnDefinitionBuilder;

/**
 * @mixin \Illuminate\Database\Schema\Blueprint
 */
class Column
{
    public function __invoke()
    {
        /**
         * Select a existed column on the table.
         *
         * @param  string  $column
         * @return \Illuminate\Database\Schema\ColumnDefinition
         */
        return function ($column) {
            /** @var \Illuminate\Database\Schema\Blueprint $this */
            return (new ColumnDefinitionBuilder($this))->get($column);
        };
    }
}
