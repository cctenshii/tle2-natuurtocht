<?php

namespace Database\Seeders;

use App\Models\NatureItem;
use Illuminate\Database\Seeder;

class NatureItemSeeder extends Seeder
{
    public function run(): void
    {
        // Items voor de categorie 'Planten' (ID = 1)
        NatureItem::create([
            'category_id' => 1,
            'number' => '001',
            'name' => 'Eik',
            'sub_group' => 'Bomenrijk',
            'image_url' => 'https://placehold.co/100x100/AFA/333?text=🌳',
        ]);

        NatureItem::create([
            'category_id' => 1,
            'number' => '002',
            'name' => 'Struik',
            'sub_group' => 'Struikenrijk',
            'image_url' => 'https://placehold.co/100x100/AFA/333?text=🌿',
        ]);
    }
}
