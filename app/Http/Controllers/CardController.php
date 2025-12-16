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

        // ✅ Zorg dat pivot bestaat (kaart is sowieso "owned" na upload flow)
        $user->cards()->syncWithoutDetaching([
            $card->id => ['acquired_at' => now()->toDateString()],
        ]);

        // ✅ Check: quiz al gedaan?
        $existing = $user->cards()->where('cards.id', $card->id)->first();
        if ($existing?->pivot?->quiz_completed_at) {
            return redirect()
                ->route('quiz', $card->id)
                ->with('info', 'Je hebt deze vraag al beantwoord!');
        }

        $quiz = DB::table('quiz')->where('card_id', $card->id)->first();
        abort_unless($quiz, 404);

        $answers = json_decode($quiz->answers ?? '[]', true) ?: [];
        $correct = collect($answers)->firstWhere('correct', true);

        $selectedId = (string) $request->input('answer_id');
        $isCorrect = $correct && ((string) $correct['id'] === $selectedId);

        // ✅ Markeer quiz altijd als gedaan (zodat je niet kunt retry’en)
        $pivotUpdate = [
            'quiz_completed_at' => now(),
            'quiz_answer_id'    => $selectedId,
            'quiz_correct'      => $isCorrect ? 1 : 0,
        ];

        if ($isCorrect) {
            $pivotUpdate['is_shiny'] = 1;
        }

        $user->cards()->updateExistingPivot($card->id, $pivotUpdate);

        // ✅ Bonuspunten alleen bij correct (15 bonus -> totaal 30)
        if ($isCorrect) {
            $alreadyBonus = $user->pointTransactions()
                ->where('action', 'card_shiny')
                ->where('card_id', $card->id)
                ->exists();

            if (!$alreadyBonus) {
                $user->awardPoints(15, 'card_shiny', $card, ['from' => 'quiz_correct']);
            }

            return redirect()
                ->route('cards.show', $card->id)
                ->with('success', 'Goed! Kaart is nu shiny 🎉 (+15 bonus, totaal 30)');
        }

        // ❌ Fout antwoord: wel klaar, maar niet shiny
        return redirect()
            ->route('cards.show', $card->id)
            ->with('info', 'Deze vraag is nu beantwoord. Helaas niet goed — geen shiny deze keer.');
    }




}
