<?php

namespace App\Http\Controllers;

use App\Models\Card;
use App\Models\NatureItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class CardController extends Controller
{

    public function show(Card $card)
    {
        $user = auth()->user();

        $ownedCard = $user?->cards()->where('cards.id', $card->id)->first();

        $owned = (bool)$ownedCard;

        $location = "Schiebroekse Polder";
        $season = "Herfst";

        return view('cards.show', [
            'card' => $card,
            'ownedCard' => $ownedCard, // <-- belangrijk
            'location' => $location,
            'season' => $season,
            'owned' => $owned,
        ]);
    }


    public function makeCardShiny(Request $request, int $id)
    {
        $user = Auth::user();
        if (!$user) {
            return redirect()->route('login');
        }

        $request->validate([
            'answer_id' => ['required', 'string'],
        ]);

        $card = Card::findOrFail($id);

        $quiz = DB::table('quiz')->where('card_id', $card->id)->first();
        abort_unless($quiz, 404);

        $answers = json_decode($quiz->answers ?? '[]', true) ?: [];
        $correct = collect($answers)->firstWhere('correct', true);

        $selectedId = (string) $request->input('answer_id');
        $isCorrect = $correct && ((string)$correct['id'] === $selectedId);

        if (!$isCorrect) {
            return redirect()->route('quiz', $card->id)
                ->withErrors(['answer_id' => 'Helaas, dat is niet goed.']);
        }

        $user->cards()->syncWithoutDetaching([
            $card->id => ['acquired_at' => now()->toDateString()],
        ]);

        $ownedCard = $user->cards()->where('cards.id', $card->id)->first();
        $wasShiny = (bool) ($ownedCard?->pivot?->is_shiny ?? false);

        if (!$wasShiny) {
            $user->cards()->updateExistingPivot($card->id, [
                'is_shiny' => 1,
            ]);

            $alreadyBonus = $user->pointTransactions()
                ->where('action', 'card_shiny')
                ->where('card_id', $card->id)
                ->exists();

            if (!$alreadyBonus) {
                $user->awardPoints(15, 'card_shiny', $card, [
                    'from' => 'quiz_correct',
                ]);
            }
        }

        return redirect()->route('cards.show', $card->id)
            ->with('success', $wasShiny ? 'Deze kaart was al shiny!' : 'Goed! Kaart is nu shiny 🎉 (+15 bonus, totaal 30)');
    }


}
