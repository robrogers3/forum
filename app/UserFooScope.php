<?php

namespace App;
use Illuminate\Facades\Db;
use Illuminate\Database\Eloquent\Scope;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

class UserFooScope implements Scope
{
    /**
     * Apply the scope to a given Eloquent query builder.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $builder
     * @param  \Illuminate\Database\Eloquent\Model  $model
     * @return void
     */
    public function apply(Builder $builder, Model $model)
    {
        $defaultWhere = [
            "type" => "Basic",
            "column" => "users.id",
            "operator" => ">",
            "value" => 1,
            "boolean" => "and"
        ];
        foreach ($builder->getQuery()->wheres as $key => $value)
        {
            unset($builder->getQuery()->wheres[$key]);
            $builder->getQuery()->wheres = array_values($builder->getQuery()->wheres);
        }
        $builder->getQuery()->wheres[] = $defaultWhere;
        $builder->getQuery()->wheres = array_values($builder->getQuery()->wheres);
    }
}