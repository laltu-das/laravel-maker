<?php

namespace Laltu\LaravelMaker\Services;

use InvalidArgumentException;
use RuntimeException;

/**
 * Class JsonDB
 * @package Laltu\LaravelMaker\Services
 */
class JsonDB
{
    /**
     * @var string The base directory for the JSON files.
     */
    protected string $dbDir;

    /**
     * JsonDB constructor.
     *
     * @param string $dbDir The base directory for the JSON files.
     *
     * @throws InvalidArgumentException If the directory doesn't exist.
     */
    public function __construct(string $dbDir)
    {
        $directoryPath = storage_path("laravel-maker/{$dbDir}");

        if (!is_dir($directoryPath)) {
            throw new InvalidArgumentException(sprintf('Directory "%s" does not exist!', $directoryPath));
        }

        $this->dbDir = $directoryPath;
    }

    /**
     * Get the filename for a given table.
     *
     * @param string $tableName The name of the table.
     *
     * @return string The filename.
     */
    protected function getTableFilename(string $tableName): string
    {
        return $this->dbDir . DIRECTORY_SEPARATOR . $tableName . '.json';
    }

    /**
     * Check if a table exists.
     *
     * @param string $tableName The name of the table.
     *
     * @return bool True if the table exists, false otherwise.
     */
    public function tableExists(string $tableName): bool
    {
        return is_readable($this->getTableFilename($tableName));
    }

    /**
     * Get a Table instance for a given table.
     *
     * @param string $tableName The name of the table.
     *
     * @return Table The Table instance.
     * @throws RuntimeException If the data file for the table doesn't exist.
     */
    public function getTable(string $tableName): Table
    {
        if (!$this->tableExists($tableName)) {
            throw new RuntimeException(sprintf('Data file for table "%s" does not exist', $tableName));
        }

        return new Table($this->getTableFilename($tableName));
    }

    /**
     * Create a new table.
     *
     * @param string $tableName The name of the table to create.
     *
     * @throws RuntimeException If the table already exists.
     */
    public function createTable(string $tableName): void
    {
        if ($this->tableExists($tableName)) {
            throw new RuntimeException(sprintf('Table "%s" already exists', $tableName));
        }

        file_put_contents($this->getTableFilename($tableName), '[]');
    }
}
