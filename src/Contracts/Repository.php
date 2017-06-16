<?php

declare(strict_mode=1);

namespace BT\Repository\Contracts;

use Illuminate\Contracts\Pagination\Paginator;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;

/**
 * Interface Repository
 * @package Mitiaj\Repository\Contracts
 */
interface Repository
{
    /**
     * Retrieve data array for populate field select
     *
     * @param array $column
     * @param null|string|null $key
     * @return Collection
     */
    public function lists(array $column, ?string $key = null): Collection;

    /**
     * Retrieve all data of repository
     *
     * @param array $columns
     *
     * @return mixed
     */
    public function all(array $columns = ['*']): Collection;

    /**
     * Retrieve all data of repository, paginated
     *
     * @param int|null|null $perPage
     * @param int|null|null $page
     * @param array $columns
     * @param string $pageName
     * @return mixed
     */
    public function paginate(?int $perPage = null, ?int $page = null, array $columns = ['*'], $pageName = 'page');

    /**
     * @param int|null|null $perPage
     * @param int|null|null $page
     * @param array $columns
     * @param string $pageName
     * @return Paginator
     */
    public function simplePaginate(
        ?int $perPage = null,
        ?int $page = null,
        array $columns = ['*'],
        $pageName = 'page'
    ): Paginator;

    /**
     * Find data by id
     *
     * @param       $id
     * @param array $columns
     *
     * @return mixed
     */
    public function find(int $id, array $columns = ['*']): Model;

    /**
     * Save a new entity in repository
     *
     * @param array $attributes
     *
     * @return mixed
     */
    public function create(array $attributes): Model;

    /**
     * Update a entity in repository by id
     *
     * @param array $attributes
     * @param       $id
     *
     * @return mixed
     */
    public function update(array $attributes, int $id);

    /**
     * Update or Create an entity in repository
     *
     * @param array $attributes
     * @param array $values
     *
     * @return mixed
     */
    public function updateOrCreate(array $attributes, array $values = []): Model;

    /**
     * Delete a entity in repository by id
     *
     * @param $id
     *
     * @return int
     */
    public function delete(int $id): int;


    public function first() : Model;

    /**
     * Load relations
     *
     * @param $relations
     *
     * @return Repository
     */
    public function with(array $relations): Repository;

    public function pushCriteria(Criteria $criteria): Repository;
}
