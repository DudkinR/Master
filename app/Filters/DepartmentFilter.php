<?php
namespace App\Filters;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use App\Filters\BaseFilter;

class DepartmentFilter extends BaseFilter
{
    protected $DepartmentId;

    public function __construct($DepartmentId)
    {
        $this->DepartmentId = $DepartmentId;
    }

    public function handle(Builder $query): Builder
    {
        return $this-> DepartmentId
            ? $query->whereHas('departments', fn($q) => $q->where('department.id', $this->DepartmentId))
            : $query;
    }
}