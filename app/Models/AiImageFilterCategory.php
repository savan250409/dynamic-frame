<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AiImageFilterCategory extends Model
{
    use HasFactory;

    protected $table = 'ai_image_filter_categories';

    protected $fillable = ['category_name', 'category_image', 'sort_order', 'status'];

    public function filters()
    {
        return $this->hasMany(AiImageFilter::class, 'category_id');
    }
}
