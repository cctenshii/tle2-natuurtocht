<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class NatureItem extends Model
{
    use HasFactory;

    protected $fillable = ['category_id', 'number', 'name', 'sub_group', 'image_url'];

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }
}
