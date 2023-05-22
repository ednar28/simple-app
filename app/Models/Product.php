<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

class Product extends BaseModel
{
    use HasFactory;
    use SoftDeletes;

    /**
     * The attributes that should be cast to native types.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'price' => 'integer',
        'amount' => 'integer',
    ];

    /**
     * Scope a query to sorting invetories.
     */
    public function scopeRequestSort(Builder $query, string $orderColumn = null, string $orderType = null): Builder
    {
        // default sort by name ascending
        $orderColumn = in_array($orderColumn, ['name', 'amount', 'price']) ? $orderColumn : 'name';
        $orderType = $orderType === 'desc' ? 'desc' : 'asc';

        return $query->orderBy($orderColumn, $orderType);
    }
}
