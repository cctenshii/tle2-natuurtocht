<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Casts\Attribute;

class NatureItem extends Model
{
    use HasFactory;

    protected $table = 'cards';

    protected $fillable = ['name', 'properties', 'description', 'category_id', 'image_url'];

    protected $casts = [
        'properties' => 'array',
    ];

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    // Seasons via pivot card_season
    public function seasons(): BelongsToMany
    {
        return $this->belongsToMany(Season::class, 'card_season', 'card_id', 'season_id');
    }

    // Handig in je view: {{ $card->season_text }}
    protected function seasonText(): Attribute
    {
        return Attribute::make(
            get: function () {
                // Als seasons al eager-loaded is, gebruik die.
                if ($this->relationLoaded('seasons')) {
                    return $this->seasons->pluck('name')->implode(', ');
                }

                // Anders haal alsnog op (lazy).
                return $this->seasons()->pluck('name')->implode(', ');
            }
        );
    }

    protected function realm(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->properties['rijk'] ?? null
        );
    }

    protected function locationText(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->properties['locatie_text'] ?? null
        );
    }

    protected function fact(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->properties['feitje'] ?? null
        );
    }
}
