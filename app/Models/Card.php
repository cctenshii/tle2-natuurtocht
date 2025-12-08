<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Casts\Attribute;

class Card extends Model
{
    protected $table = 'cards';
    protected $guarded = [];

    public $timestamps = false;

    protected $casts = [
        'properties' => 'array',
    ];


    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function seasons(): BelongsToMany
    {
        return $this->belongsToMany(Season::class, 'card_season');
    }

    public function users(): BelongsToMany
    {
        return $this->belongsToMany(\App\Models\User::class, 'user_cards')
            ->withPivot(['acquired_at', 'image_url', 'is_shiny']);
    }

    public function locations(): BelongsToMany
    {
        return $this->belongsToMany(Location::class, 'card_location');
    }

    public function quizzes(): HasMany
    {
        return $this->hasMany(Quiz::class, 'card_id');
    }


    protected function subGroup(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->properties['rijk'] ?? 'Overig'
        );
    }

    protected function imageUrl(): Attribute
    {
        return Attribute::make(
            get: fn ($value) =>
            $value ?: 'https://placehold.co/400x300/DDD/777?text=' . urlencode($this->name ?? 'Card')
        );
    }


}
