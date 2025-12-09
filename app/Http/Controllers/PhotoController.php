<?php

namespace App\Http\Controllers;

use App\Models\Card;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class PhotoController extends Controller
{
    /**
     * Update the photo for a specific card.
     *
     * @param \Illuminate\Http\Request $request
     * @param \App\Models\Card $card
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request, Card $card)
    {
        // Manual validation for AJAX request
        $validator = Validator::make($request->all(), [
            'photo' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        // If validation fails, return a proper JSON response
        if ($validator->fails()) {
            Log::error('Validatiefout bij foto-upload voor kaart ' . $card->id . ': ' . $validator->errors()->first());
            return response()->json([
                'message' => 'De foto is niet geldig. Zorg dat het een afbeelding is (jpg, png) en niet groter dan 2MB.',
                'errors' => $validator->errors()
            ], 422); // 422 Unprocessable Entity
        }

        try {
            // Delete the old image if it exists and is not a placeholder
            if ($card->image_url && !str_starts_with($card->image_url, 'http')) {
                Storage::disk('public')->delete($card->image_url);
            }

            // Store the new image in 'storage/app/public/card-photos'
            // The store method returns 'card-photos/filename.jpg'
            $path = $request->file('photo')->store('card-photos', 'public');
            if (!$path) {
                throw new \Exception("Kon het afbeeldingsbestand niet opslaan.");
            }

            // Update the card with the new relative path
            $card->image_url = $path;
            $card->save();

            // Add the card to the user's collection if not already there
            Auth::user()->cards()->syncWithoutDetaching($card->id);

            // Flash the success message to the session
            session()->flash('success', 'Foto succesvol geüpload en gekoppeld aan de kaart!');

            // Return a JSON response with the redirect URL
            return response()->json([
                'message' => 'Foto succesvol geüpload!',
                'redirect_url' => route('cards.show', $card)
            ]);

        } catch (\Exception $e) {
            // Log the real error for debugging
            Log::error('Fout bij uploaden van foto voor kaart ' . $card->id . ': ' . $e->getMessage());

            // Return a JSON response with a user-friendly error message
            return response()->json([
                'message' => 'De upload is mislukt op de server. Probeer het opnieuw.'
            ], 500);
        }
    }
}
