<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Filter extends Model
{
    protected $table = 'filters';

    protected $fillable = [
        'filter_category_id',
        'name',
        'saturation',
        'brightness',
        'contrast',
        'red',
        'green',
        'blue',
        'is_premium',
        'sort_order',
        'status',
    ];

    protected $casts = [
        'is_premium' => 'boolean',
        'status'     => 'boolean',
    ];

    public function filterCategory()
    {
        return $this->belongsTo(FilterCategory::class, 'filter_category_id');
    }
}
