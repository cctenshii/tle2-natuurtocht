<?php

namespace App\Http\Controllers;

use App\Models\Card;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class NatuurDexController extends Controller
{
    public function index(Request $request): View
    {
        $userId = Auth::id(); // null als niet ingelogd

        // Seizoen bepalen (via query of automatisch)
        $season = $request->get('season') ?? $this->getCurrentSeason();

        // Categorieën + items filteren op seizoen + pivot van ingelogde user mee laden
        $categories = Category::with([
            'items' => function ($query) use ($season) {
                $query->whereHas('seasons', fn ($q) => $q->where('name', $season))
                    ->orderBy('number'); // als number bestaat
            },
            'items.seasons',
            'items.users' => function ($q) use ($userId) {
                if ($userId) {
                    $q->where('users.id', $userId); // alleen pivot van deze user
                } else {
                    $q->whereRaw('1 = 0'); // niet ingelogd => geen users relation laden
                }
            },
        ])->get()->map(function ($category) {
            $category->grouped_items = $category->items->groupBy(fn ($item) => $item->sub_group);
            return $category;
        });

        // Locatie hardcoded
        $location = "Schiebroekse Polder";

        // Progress: maak het logisch per geselecteerd seizoen
        $totalCards = Card::whereHas('seasons', fn ($q) => $q->where('name', $season))->count();

        $collectedCards = $userId
            ? Card::whereHas('seasons', fn ($q) => $q->where('name', $season))
                ->whereHas('users', fn ($q) => $q->where('users.id', $userId))
                ->count()
            : 0;

        $percentage = $totalCards > 0
            ? round(($collectedCards / $totalCards) * 100)
            : 0;

        $seasonStyles = $this->getSeasonStyles($season);

        return view('natuur-dex.index', compact(
            'categories',
            'location',
            'season',
            'seasonStyles',
            'percentage',
            'totalCards',
            'collectedCards'
        ));
    }

    private function getCurrentSeason(): string
    {
        $month = now()->month;

        return match (true) {
            $month >= 3 && $month <= 5 => 'Lente',
            $month >= 6 && $month <= 8 => 'Zomer',
            $month >= 9 && $month <= 11 => 'Herfst',
            default => 'Winter',
        };
    }

    private function getSeasonStyles(string $season): array
    {
        return match ($season) {
            'Lente' => ['color' => 'text-green-600', 'icon' => 'icons.seed'],
            'Zomer' => ['color' => 'text-yellow-600', 'icon' => 'icons.sun'],
            'Herfst' => ['color' => 'text-orange-600', 'icon' => 'icons.leaf'],
            'Winter' => ['color' => 'text-blue-600', 'icon' => 'icons.snow'],
            default => ['color' => 'text-gray-600', 'icon' => 'icons.default'],
        };
    }
}
