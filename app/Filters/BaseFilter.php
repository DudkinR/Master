<?php
// app/Filters/BaseFilter.php
namespace App\Filters;

use Illuminate\Database\Eloquent\Builder;

abstract class BaseFilter
{
    abstract public function handle(Builder $query): Builder;
}