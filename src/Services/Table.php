<?php

namespace Laltu\LaravelMaker\Services;

/**
 * Class Table
 * @package Laltu\LaravelMaker\Services
 */
class Table
{
    /**
     * @var string The filename of the JSON data.
     */
    protected string $jsonFilename;

    /**
     * @var array The JSON data.
     */
    protected array $jsonData;

    /**
     * Table constructor.
     *
     * @param string $jsonFilename The filename of the JSON data.
     */
    public function __construct(string $jsonFilename)
    {
        $this->jsonFilename = $jsonFilename;
        $this->jsonData = json_decode(file_get_contents($this->jsonFilename), true);
    }

    /**
     * Persist the changes to the JSON data.
     */
    public function persist(): void
    {
        $json = json_encode($this->jsonData, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
        file_put_contents($this->jsonFilename, $json);
    }

    /**
     * Get all data from the table.
     *
     * @return array All data in the table.
     */
    public function getAll(): array
    {
        return $this->jsonData;
    }

    /**
     * Get the count of items in the table.
     *
     * @return int The count of items.
     */
    public function count(): int
    {
        return count($this->jsonData);
    }

    /**
     * Find items in the table based on a search criteria.
     *
     * @param array $search The search criteria.
     *
     * @return array The matching items.
     */
    public function find(array $search = []): array
    {
        if (!$search) {
            return $this->jsonData;
        }

        $filter = Matcher::factory($search);

        return array_values(array_filter($this->jsonData, function ($item) use ($filter) {
            return $filter->match($item);
        }));
    }

    /**
     * Insert a new item into the table.
     *
     * @param array $data The data to insert.
     */
    public function insert(array $data): void
    {
        $this->jsonData[] = $data;
    }

    /**
     * Delete items from the table based on a search criteria.
     *
     * @param array $search The search criteria.
     *
     * @throws \InvalidArgumentException If no search criteria are provided.
     */
    public function delete(array $search): void
    {
        if (!$search) {
            throw new \InvalidArgumentException('Missing query for delete');
        }

        $filter = Matcher::factory($search);

        $this->jsonData = array_values(array_filter($this->jsonData, function ($item) use ($filter) {
            return !$filter->match($item);
        }));
    }

    /**
     * Update items in the table based on a search criteria and update data.
     *
     * @param array $search The search criteria.
     * @param array $update The data to update.
     */
    public function update(array $search, array $update): void
    {
        $filter = Matcher::factory($search);

        $this->jsonData = array_map(function ($item) use ($filter, $update) {
            if ($filter->match($item)) {
                foreach ($update as $updateKey => $updateValue) {
                    $item[$updateKey] = $updateValue;
                }
            }
            return $item;
        }, $this->jsonData);
    }
}
