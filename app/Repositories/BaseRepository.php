<?php

namespace App\Repositories;

use Exception;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;
use App\Interfaces\BaseRepositoryInterface;

class BaseRepository implements BaseRepositoryInterface
{
    protected $model;
    protected $modelName;

    public function __construct(Model $model)
    {
        $this->model = $model;
        $this->modelName = class_basename($model);
    }

    protected function getModelName($capitalize = false, $plural = false)
    {
        $modelName = $this->modelName;

        // Capitalize the model name if requested
        if (!$capitalize) {
            $modelName = strtolower($modelName);
        }

        // Pluralize the model name if requested
        if ($plural) {
            $modelName = Str::plural($modelName);
        }

        return $modelName;
    }

    public function index()
    {
        try {
            $items = $this->model->all();

            return ['data' => $items, 'message' => "{$this->getModelName(true, true)} retrieved successfully."];
        } catch (Exception $e) {
            throw new Exception("Error fetching all {$this->getModelName(false, true)}: " . $e->getMessage(), $e->getCode() ? $e->getCode() : 500);
        }
    }

    public function show($id)
    {
        try {
            $item = $this->model->find($id);

            if (!$item) {
                return ['data' => $item, 'message' => "{$this->getModelName(true)} retrieved successfully."];
            } else {
                throw new Exception("No records found", 404);
            }
        } catch (Exception $e) {
            throw new Exception("Error fetching {$this->getModelName()} with ID $id: " . $e->getMessage(), $e->getCode() ? $e->getCode() : 500);
        }
    }

    public function store(array $data)
    {
        try {
            return DB::transaction(function () use ($data) {
                $item = $this->model->create($data);
                return ['data' => $item, 'message' => "{$this->getModelName(true)} created successfully.", 'status' => 201];
            });
        } catch (Exception $e) {
            throw new Exception("Error creating {$this->getModelName()}: " . $e->getMessage(), $e->getCode() ? $e->getCode() : 500);
        }
    }

    public function update($id, array $data)
    {
        try {
            return DB::transaction(function () use ($id, $data) {
                $item = $this->model->find($id);
                if (!$item) {
                    throw new Exception("{$this->getModelName(false, true)} not found");
                }
                $item->update($data);

                return ['data' => $item, 'message' => "{$this->getModelName(true)} updated successfully."];
            });
        } catch (Exception $e) {
            throw new Exception("Error updating {$this->getModelName()} with ID $id: " . $e->getMessage(), $e->getCode() ? $e->getCode() : 500);
        }
    }

    public function delete($id)
    {
        try {
            return DB::transaction(function () use ($id) {
                $item = $this->model->destroy($id);
                return ['data' => $item, 'message' => "{$this->getModelName(true)} deleted successfully."];
            });
        } catch (Exception $e) {
            throw new Exception("Error deleting {$this->getModelName()} with ID $id: " . $e->getMessage(), $e->getCode() ? $e->getCode() : 500);
        }
    }

    public function indexWithPaginate($perPage, array $conditions = [], $search = null, array $searchableColumns = [], array $specialSearch = [])
    {
        try {
            // Start a query builder instance on the model
            $query = $this->model->newQuery();

            // Apply conditions dynamically
            foreach ($conditions as $key => $value) {
                if ($value !== null) {
                    $query->where($key, $value);
                }
            }

            // Apply special search columns if any are provided
            foreach ($specialSearch as $column => $value) {
                if (!empty($value)) {
                    // $query->where($column, $value);
                    $query->where($column, 'LIKE', "%{$value}%");
                }
            }

            // Apply general search if a search term is provided
            if (!empty($search) && !empty($searchableColumns)) {
                $query->where(function ($q) use ($search, $searchableColumns) {
                    foreach ($searchableColumns as $column) {
                        $q->orWhere($column, 'LIKE', "%{$search}%");
                    }
                });
            }

            // Get paginated results, ordered by latest
            $items = $query->latest()->paginate($perPage);

            // Return the paginated items inside 'data'
            return ['data' => $items, 'message' => "{$this->getModelName(true, true)} retrieved successfully."];
        } catch (Exception $e) {
            throw new Exception("Error fetching all {$this->getModelName(false, true)}: " . $e->getMessage(), $e->getCode() ? $e->getCode() : 500);
        }
    }
}
