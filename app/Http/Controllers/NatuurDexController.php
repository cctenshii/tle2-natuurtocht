<?php

namespace App\Http\Controllers;

use App\Models\Category;
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
        $season = "Herfst";

        return view('natuur-dex.index', compact('categories', 'location', 'season'));
    }
}
