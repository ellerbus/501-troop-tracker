<?php

declare(strict_types=1);

namespace App\Domain\Models;

abstract class BaseModel
{
    // The name of the database table this model represents. Must be set by the child class.
    protected static string $tableName = '';
    protected static string $primaryKey = 'id';

    // Holds the actual data from the database record.
    protected array $data = [];

    // Tracks fields that have been modified since the model was last inflated or saved.
    protected array $dirtyFields = [];

    // Flag to differentiate between a new record (INSERT) and an existing one (UPDATE).
    protected bool $isNewRecord = true;

    /**
     * Magic method to get properties dynamically from the internal data array.
     * @param string $name
     * @return mixed
     */
    public function __get(string $name)
    {
        if (array_key_exists($name, $this->data))
        {
            return $this->data[$name];
        }
        // Optionally throw an exception or return null for non-existent properties
        return null;
    }

    /**
     * Magic method to set properties dynamically and mark them as "dirty" (modified).
     * @param string $name
     * @param mixed $value
     */
    public function __set(string $name, $value): void
    {
        // Only mark as dirty if the value is actually changing
        if (!array_key_exists($name, $this->data) || $this->data[$name] !== $value)
        {
            $this->data[$name] = $value;
            $this->dirtyFields[$name] = $value;
        }
    }

    public function __isset(string $name): bool
    {
        return isset($this->data[$name]);
    }

    // --- Internal/Repository-facing Methods ---

    /**
     * Get the table name.
     * @return string
     */
    public static function getTableName(): string
    {
        return static::$tableName;
    }

    /**
     * Get the table name.
     * @return string
     */
    public static function getPrimaryKey(): string
    {
        return static::$primaryKey;
    }

    /**
     * Inflates the model with data from the database.
     * Called by the Repository after fetching a record.
     * @param array $data The data record.
     * @return static
     */
    public function inflate(array $data): static
    {
        $this->data = $data;
        $this->dirtyFields = []; // Clear any dirtiness after inflation
        $this->isNewRecord = false; // It's an existing record
        return $this;
    }

    /**
     * Returns the array of fields that have been modified.
     * @return array The key is the column name, the value is the new value.
     */
    public function getDirtyFields(): array
    {
        return $this->dirtyFields;
    }

    /**
     * Returns the full internal data array.
     * @return array
     */
    public function getData(): array
    {
        return $this->data;
    }

    /**
     * Checks if the model is a new record (INSERT) or an existing one (UPDATE).
     * @return bool
     */
    public function isNewRecord(): bool
    {
        return $this->isNewRecord;
    }

    /**
     * Resets the dirty state, typically called by the Repository after a successful save/update.
     */
    public function markAsClean(): void
    {
        $this->dirtyFields = [];
        $this->isNewRecord = false;
    }

    /**
     * Creates a new instance of the Model.
     * @param array $initialData Optional initial data for a new record.
     * @return static
     */
    public static function create(array $initialData = []): static
    {
        $instance = new static();
        $instance->data = $initialData;
        $instance->dirtyFields = $initialData;
        $instance->isNewRecord = true;
        return $instance;
    }
}