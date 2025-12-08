<?php

namespace App\Http\Controllers;

use App\Models\Card;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class PhotoController extends Controller
{
    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        try {
            $request->validate([
                'image' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
            ]);

            // Store the image
            $path = $request->file('image')->store('public/card-photos');
            if (!$path) {
                throw new \Exception("Kon het afbeeldingsbestand niet opslaan.");
            }

            $url = Storage::url($path);

            // Create a new card for the user
            Card::create([
                'user_id' => Auth::id(),
                'title' => 'Nieuwe Vangst', // Tijdelijke titel
                'description' => 'Beschrijving volgt nog.', // Tijdelijke beschrijving
                'image_url' => $url,
                'rarity' => 'common', // Standaard zeldzaamheid
                'category_id' => 1, // TIJDELIJK: Standaard categorie (bv. 'Planten')
            ]);

            // Flash the success message to the session
            session()->flash('success', 'Kaart succesvol aangemaakt!');

            // For an AJAX request, a JSON response is better.
            return response()->json([
                'message' => 'Kaart succesvol aangemaakt!',
                'redirect_url' => route('dashboard')
            ]);

        } catch (\Exception $e) {
            // Log the real error for debugging
            Log::error('Fout bij uploaden van foto: ' . $e->getMessage());

            // Return a JSON response with a user-friendly error message
            return response()->json([
                'message' => 'De upload is mislukt op de server. Controleer de logs voor details.'
            ], 500);
        }
    }
}
