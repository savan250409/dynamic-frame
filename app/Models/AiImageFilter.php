<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AiImageFilter extends Model
{
    use HasFactory;

    protected $table = 'ai_image_filters';

    protected $fillable = ['category_id', 'name', 'ai_prompt', 'image_path', 'zip_file', 'sort_order'];

    public function category()
    {
        return $this->belongsTo(AiImageFilterCategory::class, 'category_id');
    }
}
