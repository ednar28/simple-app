<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Inventory extends Model
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
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @param  string  $orderColumn;
     * @param  string  $orderType;
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeRequestSort($query, string $orderColumn = null, $orderType = null)
    {
        // default sort by name ascending
        $orderColumn = in_array($orderColumn, ['name', 'amount', 'price']) ? $orderColumn : 'name';
        $orderType = $orderType === 'desc' ? 'desc' : 'asc';

        return $query->orderBy($orderColumn, $orderType);
    }
}
