<?php

declare(strict_types=1);

namespace App\Domain\Repositories;

use App\Utilities\Database;
use App\Domain\Models\BaseModel;
use \PDO;
use \InvalidArgumentException;
use \Exception;

abstract class BaseRepository
{
    // Must be set by the child class (e.g., 'UserModel::class')
    protected string $modelClass;

    public function __construct(protected readonly Database $db)
    {
    }

    /**
     * Finds a single record by its primary key.
     * @param int $id
     * @return BaseModel|null
     */
    public function get(int $id): ?BaseModel
    {
        list($table, $pk) = $this->getObjectNames();

        $sql = "SELECT * FROM {$table} WHERE {$pk} = :{$pk}";

        $data = $this->db->fetchOne($sql, [":{$pk}" => $id]);

        return $this->inflate($data);
    }

    protected function inflate(array $data): ?BaseModel
    {
        if (is_null($data))
        {
            return null;
        }

        $model = new $this->modelClass();

        return $model->inflate($data);
    }

    /**
     * Persists the Model (INSERT or UPDATE) to the database.
     * @param BaseModel $model
     * @return bool
     */
    public function save(BaseModel $model): bool
    {
        // Ensure the correct model is being saved to the correct repo
        if (!($model instanceof $this->modelClass))
        {
            throw new InvalidArgumentException('Model class mismatch in repository save operation.');
        }

        if ($model->isNewRecord())
        {
            return $this->insert($model);
        }
        else
        {
            return $this->update($model);
        }
    }

    /**
     * Handles the SQL INSERT operation.
     * @param BaseModel $model
     * @return bool
     */
    protected function insert(BaseModel $model): bool
    {
        list($table, $pk) = $this->getObjectNames();

        $data = $model->getDirtyFields();

        if (empty($data))
        {
            return true;
        }

        $columns = array_keys($data);
        $placeholders = array_map(fn($col) => ":{$col}", $columns);
        $named_parms = $this->getNamedParameters($data);

        $sql = "INSERT INTO {$table} (" . implode(', ', $columns) .
            ") VALUES (" .
            implode(', ', $placeholders) . ")";


        if ($this->db->execute($sql, $named_parms) > 0)
        {
            // Use Reflection/magic methods to set the primary key after insert
            $model->{$pk} = (int) $this->db->lastInsertId();

            $model->markAsClean();

            return true;
        }

        return false;
    }

    /**
     * Handles the SQL UPDATE operation.
     * @param BaseModel $model
     * @return bool
     */
    protected function update(BaseModel $model): bool
    {
        list($table, $pk) = $this->getObjectNames();

        $data = $model->getDirtyFields();

        if (empty($data))
        {
            return true;
        }

        // Check if the primary key exists before update
        if (!isset($model->{$pk}))
        {
            throw new Exception('Cannot update model without a primary key value.');
        }

        $set_clauses = array_map(fn($col) => "{$col} = :{$col}", array_keys($data));

        $sql = "UPDATE {$table} SET " . implode(', ', $set_clauses) . " WHERE {$pk} = :{$pk}";

        $named_parms = $this->getNamedParameters($data);

        $named_parms[":{$pk}"] = $model->$pk;

        if ($this->db->execute($sql, $named_parms) > 0)
        {
            $model->markAsClean();

            return true;
        }

        return false;
    }

    private function getNamedParameters(array $data): array
    {
        $prefixed_keys = array_map(
            fn($key) => ":$key", // Closure prepends ':' to each key
            array_keys($data) // Get the keys of the input array
        );

        // Combine the new keys with the original values
        $output = array_combine($prefixed_keys, $data);

        return $output;
    }

    private function getObjectNames(): array
    {
        $table = $this->modelClass::getTableName();
        $pk = $this->modelClass::getPrimaryKey();

        return [$table, $pk];
    }
}