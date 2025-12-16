<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class UserCardSeeder extends Seeder
{
    public function run(): void
    {
        // Pak 1 user (pas aan als je een specifieke user wil)
        $userId = DB::table('users')->value('id');

        if (!$userId) {
            $this->command?->warn('Geen users gevonden, user_cards seeder skipped.');
            return;
        }

        // Pak een paar card ids (eerste 6)
        $cardIds = DB::table('cards')->orderBy('id')->limit(6)->pluck('id');

        if ($cardIds->isEmpty()) {
            $this->command?->warn('Geen cards gevonden, user_cards seeder skipped.');
            return;
        }

        foreach ($cardIds as $i => $cardId) {
            DB::table('user_cards')->updateOrInsert(
                [
                    'user_id' => $userId,
                    'card_id' => $cardId,
                ],
                [
                    'acquired_at' => now()->toDateString(),
                    'image_url'   => $i === 0 ? 'images/cardimages/brandnetel.jpg' : null, // eerste card krijgt image
                    'is_shiny'    => $i < 2, // eerste 2 shiny
                ]
            );
        }

        // 1x startwaarde op basis van bestaande user_cards
        $rows = DB::table('user_cards')
            ->selectRaw("
        user_id,
        SUM(CASE WHEN is_shiny = 1 THEN 30 ELSE 15 END) as pts
    ")
            ->groupBy('user_id')
            ->get();

        foreach ($rows as $row) {
            DB::table('users')
                ->where('id', $row->user_id)
                ->update(['points_balance' => (int) $row->pts]);
        }
    }
}
