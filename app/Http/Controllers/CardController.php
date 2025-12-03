<?php

namespace App\Http\Controllers;

use App\Models\Card;
use App\Models\NatureItem;
use Illuminate\Http\Request;

class CardController extends Controller
{
    public function show(NatureItem $card)
    {
        return view('cards.show', [
            'card' => $card
        ]);
    }
}
