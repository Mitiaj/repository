<?php

declare(strict_mode=1);

namespace BT\Repository\Criteria;

use Illuminate\Database\Eloquent\Builder;

/**
 * Class IdEquals
 * @package Mitiaj\Repository\Criteria
 */
class IdEquals extends CriteriaAbstract
{
    /**
     * @var int
     */
    private $id;

    public function __construct(int $id)
    {
        $this->id = $id;
    }

    public function doApply(Builder $model): Builder
    {
        return $model->whereKey($this->id);
    }

    public function isValid(Builder $model): bool
    {
        return true;
    }
}
