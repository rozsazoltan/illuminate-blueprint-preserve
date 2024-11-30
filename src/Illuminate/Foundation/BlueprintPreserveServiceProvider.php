<?php

namespace Rose\BlueprintPreserve\Foundation;

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\ServiceProvider;
use Rose\BlueprintPreserve\Database\Schema\Blueprint\Column;
use Rose\BlueprintPreserve\Foundation\Exceptions\ColumnMacroAlreadyExistsException;

class BlueprintPreserveServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->registerBlueprintColumnMacro();
    }

    private function registerBlueprintColumnMacro()
    {
        $this->ensureColumnMacroDoesNotExist();

        Blueprint::macro('column', Column::class);
    }

    private function ensureColumnMacroDoesNotExist()
    {
        if (Blueprint::hasMacro('column')) {
            throw new ColumnMacroAlreadyExistsException();
        }
    }
}
