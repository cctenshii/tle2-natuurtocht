<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\View\View;

class NatuurDexController extends Controller
{
    public function index(): View
    {
        // Laad categorieën + items + seasons (voor sub_group fallback via seasons)
        $categories = Category::with(['items' => function ($query) {
            // Als "number" niet bestaat in je nieuwe schema, laat dit weg.
            $query->orderBy('number');
        }, 'items.seasons'])->get()->map(function ($category) {
            // grouped_items zoals je blade verwacht: [subGroup => items]
            $category->grouped_items = $category->items->groupBy(fn ($item) => $item->sub_group);
            return $category;
        });

        // Deze data kan later uit een andere bron komen
        $location = "Schiebroekse Polder";


        // Huidig seizoen wordt bepaald obv huidige maand en meegestuurd naar de view
        $month = Carbon::now()->month;
        $season = match (true) {
            $month >= 3 && $month <= 5 => 'Lente',
            $month >= 6 && $month <= 8 => 'Zomer',
            $month >= 9 && $month <= 11 => 'Herfst',
            default => 'Winter',
        };

        $seasonStyles = match ($season) {
            'Lente' => [
                'color' => 'text-green-600',
                'icon' => 'icons.seed',
            ],
            'Zomer' => [
                'color' => 'text-yellow-600',
                'icon' => 'icons.sun',
            ],
            'Herfst' => [
                'color' => 'text-orange-600',
                'icon' => 'icons.leaf',
            ],
            'Winter' => [
                'color' => 'text-blue-600',
                'icon' => 'icons.snow',
            ],
            default => [
                'color' => 'text-gray-600',
                'icon' => 'icons.default',
            ]
        };

        return view('natuur-dex.index', compact('categories', 'location', 'season', 'seasonStyles'));
    }
}
