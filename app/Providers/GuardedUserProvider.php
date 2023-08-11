<?php

namespace App\Providers;

use Illuminate\Auth\EloquentUserProvider;
use Illuminate\Contracts\Hashing\Hasher as HasherContract;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\ServiceProvider;

class GuardedUserProvider extends EloquentUserProvider
{

    protected string $guard;

    public function __construct(HasherContract $hasher, $model, $guard)
    {
        parent::__construct($hasher, $model);

        $this->guard = $guard;
    }

    protected function newModelQuery($model = null)
    {
        $guard = $this->guard;

        return parent::newModelQuery($model)
            ->select('*')
            ->whereHas('roles', function(Builder $query) use ($guard) {
                $query->where('guard_name', $guard);
            })
        ;
    }
}
