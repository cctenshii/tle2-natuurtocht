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
            Log::error('Validatiefout bij foto-upload voor kaart ' . $card->id . ': ' . $validator->errors()->first());
            return response()->json([
                'message' => 'De foto is niet geldig. Zorg dat het een afbeelding is (jpg, png) en niet groter dan 2MB.',
                'errors' => $validator->errors(),
            ], 422);
        }

        try {
            // 1) Zorg dat user de card alvast "owned" is (pivot bestaat dan zeker)
            $user->cards()->syncWithoutDetaching([
                $card->id => [
                    'acquired_at' => now()->toDateString(),
                ],
            ]);

            // 2) Haal de bestaande pivot op (zodat we oude foto kunnen verwijderen)
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

            // unieke filename
            $ext = $file->getClientOriginalExtension();
            $filename = 'user_' . $user->id . '_card_' . $card->id . '_' . time() . '.' . $ext;

            // move naar public/images/cardimages/...
            $file->move($dir, $filename);

            // path zoals jij in DB zet (zonder public_path)
            $relativePath = 'images/cardimages/' . $filename;

            // 4) Oude pivot foto verwijderen (alleen als het een lokale file is)
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
