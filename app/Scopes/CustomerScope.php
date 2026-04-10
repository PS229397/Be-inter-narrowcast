<?php

namespace App\Scopes;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Scope;

class CustomerScope implements Scope
{
    public function __construct(
        protected ?int $customerId,
    ) {}

    public function apply(Builder $builder, Model $model): void
    {
        if (! $this->customerId) {
            return;
        }

        $builder->where(
            $model->qualifyColumn('customer_id'),
            $this->customerId,
        );
    }
}

