<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\View\View;

class NatuurDexController extends Controller
{
    public function index(): View
    {
        // Haal alle categorieën op, en laad direct de bijbehorende items (eager loading)
        // We groeperen de items per subgroep binnen elke categorie
        $categories = Category::with(['items' => function ($query) {
            $query->orderBy('id');
        }])->get()->map(function ($category) {
            $category->grouped_items = $category->items->groupBy('sub_group');
            return $category;
        });

        // Deze data kan later uit een andere bron komen
        $location = "Schiebroekse Polder";
        $season = "Herfst";

        return view('natuur-dex.index', compact('categories', 'location', 'season'));
    }
}
