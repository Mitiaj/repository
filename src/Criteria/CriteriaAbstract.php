<?php

declare(strict_mode=1);

namespace BT\Repository\Criteria;

use BT\Repository\Contracts\Criteria;
use Illuminate\Database\Eloquent\Builder;

/**
 * Class CriteriaAbstract
 * @package Mitiaj\Repository\Criteria
 */
abstract class CriteriaAbstract implements Criteria
{
    public function apply(Builder $model): Builder
    {
        if (!$this->isValid($model)) {
            throw new \InvalidArgumentException($this->buldExceptionMessage($model));
        }

        return $this->doApply($model);
    }

    public abstract function isValid(Builder $model): bool;

    public abstract function doApply(Builder $model) : Builder;

    private function buldExceptionMessage(Builder $model): string
    {
        return 'Criteria ' . get_class($this) . ' is not valid for model ' . get_class($model->getModel());
    }
}
