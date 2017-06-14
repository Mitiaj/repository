<?php

declare(strict_mode=1);

namespace Mitiaj\Repository\Criteria;

use Illuminate\Database\Eloquent\Builder;

/**
 * Class CompareProperty
 * @package Mitiaj\Repository\Criteria
 */
class CompareProperty extends CriteriaAbstract
{
    /**
     * @var string
     */
    private $property;
    /**
     * @var string
     */
    private $operator;
    /**
     * @var string
     */
    private $value;

    public function __construct(string $property, string $operator, string $value)
    {
        $this->property = $property;
        $this->operator = $operator;
        $this->value = $value;
    }

    public function doApply(Builder $model): Builder
    {
        return $model->where($this->property, $this->operator, $this->value);
    }

    public function isValid(Builder $model): bool
    {
        return in_array($this->property, $model->getModel()->getFillable())
            && in_array($this->operator, ['=', '>', '<']);
    }
}