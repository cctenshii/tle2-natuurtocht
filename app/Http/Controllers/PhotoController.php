<?php

namespace App\Http\Controllers;

use App\Models\Card;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class PhotoController extends Controller
{
    public function store(Request $request, Card $card)
    {
        $user = Auth::user();

        if (!$user) {
            return response()->json(['message' => 'Je moet ingelogd zijn.'], 401);
        }

        // Manual validation for AJAX request
        $validator = Validator::make($request->all(), [
            'photo' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        if ($validator->fails()) {
            // ... (bestaande logica) ...
            return response()->json([
                'message' => 'De foto is niet geldig. Zorg dat het een afbeelding is (jpg, png) en niet groter dan 2MB.',
                'errors' => $validator->errors(),
            ], 422);
        }

        // --- WIZARD OF OZ LOGICA ---
        // Check of de 'wizard_correct' flag is meegestuurd en waar is.
        // Als Q niet is ingedrukt, is de waarde '0' of null.
        $isSimulatedCorrect = $request->input('wizard_correct') === '1';

        if (!$isSimulatedCorrect) {
            // Simuleer dat de AI de foto afkeurt
            return response()->json([
                'message' => 'Helaas, de foto wordt niet herkend als een ' . $card->title . '. Probeer het opnieuw en zorg dat het onderwerp goed zichtbaar is.',
            ], 422);
        }
        // ---------------------------

        try {
            // ... (De rest van je bestaande opslag logica blijft hieronder hetzelfde) ...

            // 1) Zorg dat user de card alvast "owned" is
            $user->cards()->syncWithoutDetaching([
                $card->id => [
                    'acquired_at' => now()->toDateString(),
                ],
            ]);

            // ... enzovoorts ...

            // (Ik heb de rest van de functie hier ingekort voor leesbaarheid,
            // maar laat de originele code staan die hieronder volgt in je bestand)

            // 2) Haal de bestaande pivot op
            $ownedCard = $user->cards()
                ->where('cards.id', $card->id)
                ->first();

            $oldPivotImage = $ownedCard?->pivot?->image_url;

            // 3) Bewaar naar public/images/cardimages
            $file = $request->file('photo');

            $dir = public_path('images/cardimages');
            if (!is_dir($dir)) {
                mkdir($dir, 0755, true);
            }

            $ext = $file->getClientOriginalExtension();
            $filename = 'user_' . $user->id . '_card_' . $card->id . '_' . time() . '.' . $ext;

            $file->move($dir, $filename);
            $relativePath = 'images/cardimages/' . $filename;

            // 4) Oude pivot foto verwijderen
            if ($oldPivotImage && !str_starts_with($oldPivotImage, 'http')) {
                $oldFullPath = public_path($oldPivotImage);
                if (file_exists($oldFullPath)) {
                    @unlink($oldFullPath);
                }
            }

            // 5) Pivot updaten
            $user->cards()->updateExistingPivot($card->id, [
                'image_url' => $relativePath,
            ]);

            session()->flash('success', 'Foto succesvol geüpload en gekoppeld aan jouw kaart!');

            return response()->json([
                'message' => 'Foto succesvol geüpload!',
                'redirect_url' => route('cards.show', $card),
            ]);

        } catch (\Exception $e) {
            Log::error('Fout bij uploaden van foto voor kaart ' . $card->id . ': ' . $e->getMessage());

            return response()->json([
                'message' => 'De upload is mislukt op de server. Probeer het opnieuw.',
            ], 500);
        }
    }
}
