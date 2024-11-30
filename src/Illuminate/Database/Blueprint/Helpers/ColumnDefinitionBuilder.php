<?php

namespace Rose\BlueprintPreserve\Database\Schema\Blueprint\Helpers;

use Rose\BlueprintPreserve\Database\Schema\Blueprint\Enums\BlueprintType;
use Rose\BlueprintPreserve\Database\Schema\Blueprint\Exceptions\ColumnNotFoundException;
use Rose\BlueprintPreserve\Database\Schema\Blueprint\Exceptions\InvalidColumnDefaultValueException;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Schema\ColumnDefinition;
use Illuminate\Support\Collection;
use Schema;

class ColumnDefinitionBuilder
{
    private ColumnDefinition $definition;
    private string $table;
    private string $column;
    private Collection $columns;

    public function __construct(private Blueprint $blueprint)
    {
        $this->table = $this->blueprint->getTable();
        $this->columns = collect(Schema::getColumns($this->table));
    }

    public function get(string $column): ColumnDefinition
    {
        $this->column = $column;

        $columnData = $this->getColumnData();
        if (! $columnData) {
            throw new ColumnNotFoundException($this->table, $column);
        }

        $this->definition = $this->applyColumnType($columnData['type_name'], $columnData['type_details']);
        $this->applyColumnProperties($columnData);

        return $this->definition->change();
    }

    /**
     * Get the column data from the database.
     */
    private function getColumnData(): ?array
    {
        return $this->columns->firstWhere('name', $this->column);
    }

    /**
     * Apply the column type and its parameters.
     */
    private function applyColumnType(string $type, array $parameters): ColumnDefinition
    {
        return $this->blueprint->addColumn($this->getLaravelTypeName($type), $this->column, $parameters);
    }

    private function getLaravelTypeName(string $type): string
    {
        return BlueprintType::fromSqlType($type)->value;
    }

    /**
     * Apply column properties (nullable, default, unsigned, etc.).
     */
    private function applyColumnProperties(array $columnData): void
    {
        $this->definition->autoIncrement($columnData['auto_increment']);

        if ($columnData['unsigned']) {
            $this->definition->unsigned();
        }

        if ($columnData['nullable']) {
            $this->definition->nullable();
        }

        if (! is_null($columnData['default'])) {
            $default = $this->getColumnDefaultValue($columnData['type'], $columnData['default']);
            $this->definition->default($default);
        }

        if (! is_null($columnData['comment'])) {
            $this->definition->comment($columnData['comment']);
        }
    }

    private function getColumnDefaultValue(string $type, ?string $value)
    {
        $type = strtolower($column['type'] ?? 'string');

        if (is_null($value)) {
            throw new InvalidColumnDefaultValueException($this->column);
        }

        $default = trim($value, "'\"");

        return match ($type) {
            'int', 'integer', 'bigint', 'smallint', 'tinyint' => (int) $default,
            'float', 'double', 'decimal' => (float) $default,
            'boolean', 'bool' => filter_var($default, FILTER_VALIDATE_BOOLEAN),
            'json' => json_decode($default, true) ?? '{}',
            'binary', 'varbinary', 'blob' => base64_decode($default),
            default => $default,
        };
    }
}
