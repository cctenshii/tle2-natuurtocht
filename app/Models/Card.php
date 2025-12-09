<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Support\Str;

class Card extends Model
{
    protected $table = 'cards';
    protected $guarded = [];

    public $timestamps = true;

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

    // Fallback voor cards.image_url
    protected function imageUrl(): Attribute
    {
        return Attribute::make(
            get: fn ($value) =>
            $value ?: 'https://placehold.co/400x300/DDD/777?text=' . urlencode($this->title ?? 'Card')
        );
    }

    // ✅ De URL die je overal wil gebruiken (dex + show)
    protected function displayImageUrl(): Attribute
    {
        return Attribute::make(
            get: function () {
                // 1) Als Card via auth()->user()->cards() is geladen
                $pivotImage = $this->pivot->image_url ?? null;

                // 2) Als Card via Category::with('items.users') is geladen
                if (!$pivotImage && $this->relationLoaded('users')) {
                    $pivotImage = $this->users->first()?->pivot?->image_url;
                }

                $value = $pivotImage ?: $this->image_url;

                // als het al een volledige URL is: return direct
                if (Str::startsWith((string) $value, ['http://', 'https://'])) {
                    return $value;
                }

                // anders is het een pad in public/ (bv images/cardimages/brandnetel.jpg)
                return $value ? asset($value) : $this->image_url;
            }
        );
    }
    protected function locatieText(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->properties['locatie_text'] ?? null
        );
    }

    protected function feitje(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->properties['feitje'] ?? null
        );
    }

}
