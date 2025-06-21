<?php
// RoleFilter.php
namespace App\Filters;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use App\Filters\BaseFilter;

class RoleFilter extends BaseFilter
{
    protected $roleId;

    public function __construct($roleId)
    {
        $this->roleId = $roleId;
    }

    public function handle(Builder $query): Builder
    {
        return $this->roleId
            ? $query->whereHas('roles', fn($q) => $q->where('roles.id', $this->roleId))
            : $query;
    }
}