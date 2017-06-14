<?php

declare(strict_mode=1);

namespace BT\Repository\Contracts;

use Illuminate\Database\Eloquent\Builder;

/**
 * Interface Criteria
 * @package Mitiaj\Repository\Contracts
 */
interface Criteria
{
    /**
     * Apply criteria
     *
     * @param Builder $model
     * @return Builder
     */
    public function apply(Builder $model): Builder;

    /**
     * Validate if criteria can be applied on current model
     *
     * @param Builder $model
     * @return bool
     */
    public function isValid(Builder $model): bool;
}
