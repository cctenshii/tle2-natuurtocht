<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Casts\Attribute;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    private const LEVEL_THRESHOLDS = [-1 ,0, 100, 250, 500, 1000, 2000];

    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function cards(): BelongsToMany
    {
        return $this->belongsToMany(\App\Models\Card::class, 'user_cards')
            ->withPivot(['acquired_at', 'image_url', 'is_shiny']);
    }

    public function pointTransactions(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(PointTransaction::class);
    }

    // Handig: altijd via ledger uitrekenen (altijd correct)
    public function getPointsTotalAttribute(): int
    {
        return (int) $this->pointTransactions()->sum('points');
    }

    // Handig om snel te testen/werken: 1 functie die logt + balance bijwerkt
    public function awardPoints(int $points, string $action, ?Card $card = null, array $meta = []): PointTransaction
    {
        $tx = $this->pointTransactions()->create([
            'card_id' => $card?->id,
            'action'  => $action,
            'points'  => $points,
            'meta'    => $meta ?: null,
        ]);

        $this->increment('points_balance', $points);

        return $tx;
    }

    protected function level(): Attribute
    {
        return Attribute::make(
            get: function () {
                $points = (int) ($this->points_balance ?? 0);

                $level = 0;
                foreach (self::LEVEL_THRESHOLDS as $i => $minPoints) {
                    if ($points >= $minPoints) $level = $i;
                }

                return $level;
            }
        );
    }

    protected function currentLevelMinPoints(): Attribute
    {
        return Attribute::make(
            get: fn () => self::LEVEL_THRESHOLDS[(int) $this->level] ?? 0
        );
    }

    protected function nextLevelPoints(): Attribute
    {
        return Attribute::make(
            get: fn () => self::LEVEL_THRESHOLDS[((int) $this->level) + 1] ?? null
        );
    }

    protected function levelProgressPercent(): Attribute
    {
        return Attribute::make(
            get: function () {
                $points = (int) ($this->points_balance ?? 0);
                $min = (int) ($this->current_level_min_points ?? 0);
                $next = $this->next_level_points;

                if ($next === null) return 100;

                $range = max(1, ((int) $next) - $min);
                $inside = min($range, max(0, $points - $min));

                return (int) round(($inside / $range) * 100);
            }
        );
    }
}
