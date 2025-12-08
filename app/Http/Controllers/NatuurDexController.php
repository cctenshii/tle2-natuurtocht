<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class NatuurDexController extends Controller
{
    public function index(): View
    {
        $userId = Auth::id(); // null als niet ingelogd

        $categories = Category::with([
            'items' => function ($query) {
                $query->orderBy('number'); // als number bestaat
            },
            'items.seasons',
            // 👇 dit is de key: laad ownership pivot van de ingelogde user mee
            'items.users' => function ($q) use ($userId) {
                if ($userId) {
                    $q->where('users.id', $userId);
                } else {
                    // niet ingelogd => laad niets (pivot blijft leeg)
                    $q->whereRaw('1 = 0');
                }
            },
        ])->get()->map(function ($category) {
            $category->grouped_items = $category->items->groupBy(fn ($item) => $item->sub_group);
            return $category;
        });

        $location = "Schiebroekse Polder";

        $month = Carbon::now()->month;
        $season = match (true) {
            $month >= 3 && $month <= 5 => 'Lente',
            $month >= 6 && $month <= 8 => 'Zomer',
            $month >= 9 && $month <= 11 => 'Herfst',
            default => 'Winter',
        };

        $seasonStyles = match ($season) {
            'Lente' => ['color' => 'text-green-600', 'icon' => 'icons.seed'],
            'Zomer' => ['color' => 'text-yellow-600', 'icon' => 'icons.sun'],
            'Herfst' => ['color' => 'text-orange-600', 'icon' => 'icons.leaf'],
            'Winter' => ['color' => 'text-blue-600', 'icon' => 'icons.snow'],
            default => ['color' => 'text-gray-600', 'icon' => 'icons.default'],
        };

        return view('natuur-dex.index', compact('categories', 'location', 'season', 'seasonStyles'));
    }
}
