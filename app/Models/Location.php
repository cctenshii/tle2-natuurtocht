<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Location extends Model
{
    protected $guarded = [];

    public $timestamps = false;

    public function cards(): BelongsToMany
    {
        return $this->belongsToMany(Card::class, 'card_location');
    }
}
