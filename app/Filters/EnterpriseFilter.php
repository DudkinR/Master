<?php
// EnterpriseFilter.php
namespace App\Filters;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use App\Filters\BaseFilter;

class EnterpriseFilter extends BaseFilter
{
    protected $enterpriseId;

    public function __construct($enterpriseId)
    {
        $this->enterpriseId = $enterpriseId;
    }

    public function handle(Builder $query): Builder
    {
        return $this->enterpriseId
            ? $query->whereHas('enterprises', fn($q) => $q->where('enterprise.id', $this->enterpriseId))
            : $query;
    }
    // enterpriseIds
    public function enterpriseIds(array $enterpriseIds): Builder
    {
        return User::whereHas('enterprises', function ($query) use ($enterpriseIds) {
            $query->whereIn('id', $enterpriseIds);
        });
    }
}