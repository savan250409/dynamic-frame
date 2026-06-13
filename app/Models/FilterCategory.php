<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FilterCategory extends Model
{
    protected $table = 'filter_categories';

    protected $fillable = [
        'name',
        'image',
        'sort_order',
        'status',
    ];

    protected $casts = [
        'status' => 'boolean',
    ];

    public function filters()
    {
        return $this->hasMany(Filter::class, 'filter_category_id');
    }
}
