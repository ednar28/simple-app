<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BaseModel extends Model
{
    /**
     * The number of items to be shown per page.
     */
    protected $perPage = 50;
}
