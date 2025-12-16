<?php

namespace App\Http\Controllers;

use App\Models\Card;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class QuizController extends Controller
{
    public function showQuiz(int $id)
    {
        $user = auth()->user();
        if (!$user) {
            return redirect()->route('login');
        }

        // Check of deze user deze quiz al gedaan heeft (via user_cards pivot)
        $existing = $user->cards()->where('cards.id', $id)->first();
        if ($existing?->pivot?->quiz_completed_at) {
            return redirect()
                ->route('cards.show', $id)
                ->with('info', 'Je hebt deze vraag al beantwoord!');
        }

        $tableData = DB::table('quiz')
            ->where('card_id', '=', $id)
            ->first();

        abort_unless($tableData, 404);

        // No-cache zodat back-button niet de oude quiz uit cache toont
        return response()
            ->view("quiz", ['data' => $tableData, 'idCard' => $id])
            ->header('Cache-Control', 'no-store, no-cache, must-revalidate, max-age=0')
            ->header('Pragma', 'no-cache')
            ->header('Expires', '0');
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
