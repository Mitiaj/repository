<?php

declare(strict_mode=1);

namespace BT\Repository;

use BT\Repository\Contracts\Criteria;
use BT\Repository\Contracts\Repository;
use BT\Repository\Criteria\CompareProperty;
use BT\Repository\Criteria\IdEquals;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Contracts\Pagination\Paginator;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;

abstract class RepositoryAbstract implements Repository
{
    public function __construct()
    {
        $this->reset();
    }

    /**
     * @var Builder
     */
    protected $model;

    /**
     * @var string[]
     */
    protected $criteria = [];

    /**
     * @var Criteria[]
     */
    protected $relations = [];

    public function lists(array $column, ?string $key = null): Collection
    {
        $this->applyAllCriteria();
        $this->applyRelations();

        $result = $this->model->lists($column, $key);
        $this->reset();

        return $result;
    }

    public function all(array $columns = ['*']): Collection
    {
        $this->applyAllCriteria();
        $this->applyRelations();

        $result = $this->model->get($columns);
        $this->reset();

        return $result;
    }

    public function paginate(
        ?int $perPage = null,
            ?int $page = null,
            array $columns = ['*'],
            $pageName = 'page'
    ) : LengthAwarePaginator
    {
        $this->applyAllCriteria();
        $this->applyRelations();

        $result = $this->model->paginate($perPage, $columns, $pageName, $page);
        $this->reset();

        return $result;
    }

    public function simplePaginate(
        ?int $perPage = null,
        ?int $page = null,
        array $columns = ['*'],
        $pageName = 'page'
    ) : Paginator
    {
        $this->applyAllCriteria();
        $this->applyRelations();

        $result = $this->model->simplePaginate($perPage, $columns, $pageName, $page);
        $this->reset();

        return $result;
    }

    public function find(int $id, array $columns = ['*']): Model
    {
        $this->applyAllCriteria();
        $this->applyRelations();

        $result = $this->applyCriteria(new IdEquals($id))->firstOrFail($columns)->getModel();
        $this->reset();

        return $result;
    }

    public function create(array $attributes): Model
    {
        $result = $this->model->create($attributes);
        $this->reset();

        return $result;
    }

    public function update(array $attributes, int $id) : int
    {
        $result = $this->applyCriteria(new IdEquals($id))->update($attributes);
        $this->reset();

        return $result;
    }

    public function updateOrCreate(array $attributes, array $values = []) : Model
    {
        $this->applyAllCriteria();
        $this->applyRelations();

        $result = $this->model->updateOrCreate($attributes, $values);
        $this->reset();

        return $result;
    }

    public function delete(int $id): int
    {
        $this->applyAllCriteria();

        $result = $this->applyCriteria(new IdEquals($id))->delete();
        $this->reset();

        return $result;
    }

    public function first(): Model
    {
        $this->applyAllCriteria();
        $this->applyRelations();

        $result = $this->model->first();
        $this->reset();

        return $result;
    }

    public function firstOrDefault(): ?Model
    {
        $this->applyAllCriteria();
        $this->applyRelations();

        $result = $this->model->first();
        $this->reset();

        return $result;
    }

    public function count(array $attributes = []): int
    {
        foreach ($attributes as $key => $value) {
            $this->pushCriteria(new CompareProperty($key, '=', $value));
        }

        $this->applyAllCriteria();
        $this->applyRelations();

        $result = $this->model->count();
        $this->reset();

        return $result;
    }

    public function exists(array $attributes = []): bool
    {
        foreach ($attributes as $key => $value) {
            $this->pushCriteria(new CompareProperty($key, '=', $value));
        }

        $this->applyAllCriteria();
        $this->applyRelations();

        $result = $this->model->exists();
        $this->reset();

        return $result;
    }

    public function with(array $relations): Repository
    {
        $this->relations = $relations;

        return $this;
    }

    public function pushCriteria(Criteria $criteria): Repository
    {
        $this->criteria[] = $criteria;

        return $this;
    }

    protected function reset(): void
    {
        $this->relations = [];
        $this->criteria = [];
        $this->model = $this->buildModel();
    }

    protected abstract function model(): string;

    private function applyRelations(): void
    {
        if (!empty($this->relations)) {
            $this->model->with($this->relations);
        }
    }

    protected function applyAllCriteria(): void
    {
        foreach ($this->criteria as $criteria) {
            if ($criteria instanceof Criteria) {
                $this->applyCriteria($criteria);
            }
        }
    }

    private function applyCriteria(Criteria $criteria): Builder
    {
        return $this->model = $criteria->apply($this->model);
    }

    private function buildModel(): Builder
    {
        $model = $this->model();

        return (new $model())->newQuery();
    }
}
