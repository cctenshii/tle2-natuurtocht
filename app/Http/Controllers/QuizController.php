<?php

namespace App\Http\Controllers;

use App\Models\Card;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class QuizController extends Controller
{
    public function showQuiz(int $cardId)
    {
        $quiz = DB::table('quiz')->where('card_id', $cardId)->first();
        abort_unless($quiz, 404);

        $answers = json_decode($quiz->answers ?? '[]', true) ?: [];

        return view('quiz', [
            'data' => $quiz,
            'answers' => $answers,
            'idCard' => $cardId,
        ]);
    }

    public function submitQuiz(Request $request, int $cardId)
    {
        $quiz = DB::table('quiz')->where('card_id', $cardId)->first();
        abort_unless($quiz, 404);

        $request->validate([
            'answer_id' => ['required', 'string'],
        ]);

        $answers = json_decode($quiz->answers ?? '[]', true) ?: [];

        $selectedId = (string) $request->input('answer_id');
        $selected = collect($answers)->firstWhere('id', $selectedId);
        $correct  = collect($answers)->firstWhere('correct', true);

        $isCorrect = $selected && $correct && ($selected['id'] === $correct['id']);

        if (!$isCorrect) {
            return back()->withErrors(['answer_id' => 'Helaas, dat is niet goed. Probeer het nog eens!'])->withInput();
        }

        $user = Auth::user();
        if (!$user) {
            return redirect()->route('login');
        }

        // voorkom dubbel punten geven voor dezelfde kaart/actie
        $alreadyAwarded = $user->pointTransactions()
            ->where('action', 'quiz_correct')
            ->where('card_id', $cardId)
            ->exists();

        if ($alreadyAwarded) {
            return redirect()->route('cards.show', $cardId)
                ->with('success', 'Goed gedaan! (Punten voor deze kaart had je al gekregen.)');
        }

        // Zorg dat de kaart in user_cards staat (pivot bestaat)
        $user->cards()->syncWithoutDetaching([
            $cardId => [
                'acquired_at' => now()->toDateString(),
            ],
        ]);

        // Pivot ophalen om shiny te bepalen
        $ownedCard = $user->cards()->where('cards.id', $cardId)->first();
        $isShiny = (bool) ($ownedCard?->pivot?->is_shiny ?? false);

        $points = $isShiny ? 30 : 15;

        $card = Card::findOrFail($cardId);
        $user->awardPoints($points, 'quiz_correct', $card, [
            'selected_answer_id' => $selectedId,
            'is_shiny' => $isShiny,
        ]);

        return redirect()->route('cards.show', $cardId)
            ->with('success', $isShiny
                ? 'Goed! Shiny kaart 🎉 +30 punten!'
                : 'Goed! +15 punten!');
    }
}
