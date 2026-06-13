<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StickerCategory extends Model
{
    protected $table = 'sticker_categories';

    protected $fillable = [
        'category_name',
        'image',
        'stickers',
        'is_premium',
        'sort_order',
        'status',
    ];

    protected $casts = [
        'stickers'   => 'array',
        'is_premium' => 'boolean',
        'status'     => 'boolean',
    ];
}
