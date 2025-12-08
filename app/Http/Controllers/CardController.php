<?php

namespace App\Http\Controllers;

use App\Models\Card;
use App\Models\NatureItem;
use Illuminate\Http\Request;

class CardController extends Controller
{
    public function show(NatureItem $card)
    {
        $user = auth()->user();

        // Controleer of de gebruiker deze kaart heeft (via de user_cards pivot)
        $owned = $user ? $user->cards()->where('cards.id', $card->id)->exists() : false;

        $location = "Schiebroekse Polder";
        $season = "Herfst";

        return view('cards.show', [
            'card' => $card,
            'location' => $location,
            'season' => $season,
            'owned' => $owned,
        ]);
    }

}
