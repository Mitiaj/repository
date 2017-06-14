<?php

declare(strict_mode=1);

namespace BT\Repository\Criteria;

use Illuminate\Database\Eloquent\Builder;

/**
 * Class OrderBy
 * @package Mitiaj\Repository\Criteria
 */
class OrderBy extends CriteriaAbstract
{
    /**
     * @var string
     */
    private $column;
    /**
     * @var string
     */
    private $direction;

    public function __construct(string $column, string $direction)
    {
        $this->column = $column;
        $this->direction = $direction;
    }

    public function isValid(Builder $model): bool
    {
        return in_array($this->column, $model->getModel()->getFillable())
            && in_array($this->direction, ['asc', 'desc']);
    }

    public function doApply(Builder $model): Builder
    {
        return $model->orderBy($this->column, $this->direction);
    }
}
