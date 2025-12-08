<?php
//
//namespace Database\Seeders;
//
//use Illuminate\Database\Seeder;
//use Illuminate\Support\Facades\DB;
//use Illuminate\Support\Facades\Schema;
//use Illuminate\Support\Str;
//
//use App\Models\Card;
//use App\Models\Category;
//use App\Models\Season;
//use App\Models\Location;
//
//// Als je model anders heet: pas dit aan.
//// Belangrijk: als jouw tabel letterlijk "quiz" heet, zet in het Quiz-model: protected $table = 'quiz';
//use App\Models\Quiz;
//
//class ImportCardsFromCsvSeeder extends Seeder
//{
//    public function run(): void
//    {
//        $path = storage_path('app/seed/cards.csv');
//        if (!file_exists($path)) {
//            throw new \RuntimeException("CSV not found at: {$path}");
//        }
//
//        // Seizoenen (basis) aanmaken
//        $seasonMap = [
//            'lente'  => 'Lente',
//            'zomer'  => 'Zomer',
//            'herfst' => 'Herfst',
//            'winter' => 'Winter',
//        ];
//        foreach ($seasonMap as $name) {
//            Season::firstOrCreate(['name' => $name]);
//        }
//
//        $file = new \SplFileObject($path);
//        $file->setFlags(\SplFileObject::READ_CSV | \SplFileObject::SKIP_EMPTY);
//
//        $header = null;
//
//        DB::transaction(function () use ($file, &$header, $seasonMap) {
//            foreach ($file as $row) {
//                if (!$row || (count($row) === 1 && $row[0] === null)) {
//                    continue;
//                }
//
//                // header row
//                if ($header === null) {
//                    $header = array_map(fn ($h) => trim((string)$h), $row);
//                    continue;
//                }
//
//                // map row to header
//                $row = array_pad($row, count($header), null);
//                $data = @array_combine($header, $row);
//                if (!$data) continue;
//
//                $name = trim((string)($data['Naam'] ?? ''));
//                if ($name === '') continue;
//
//                $soort = trim((string)($data['Soort'] ?? 'Onbekend'));
//                $category = Category::firstOrCreate(['name' => $soort]);
//
//                $kenmerken = trim((string)($data['Kenmerken'] ?? ''));
//                $locatieText = trim((string)($data['Locatie'] ?? ''));
//                $feitje = trim((string)($data['Feitje'] ?? ''));
//
//                // Card upsert (op naam)
//                $card = Card::updateOrCreate(
//                    ['name' => $name],
//                    [
//                        'category_id' => $category->id,
//                        // pas aan als je kolom NOT NULL is
//                        'description' => $kenmerken !== '' ? $kenmerken : null,
//                        // json properties
//                        'properties' => [
//                            'feitje' => $feitje !== '' ? $feitje : null,
//                            'locatie_text' => $locatieText !== '' ? $locatieText : null,
//                        ],
//                    ]
//                );
//
//                // Seasons pivot: detecteer woorden in "Seizoen" veld
//                $seasonText = Str::lower((string)($data['Seizoen'] ?? ''));
//                $seasonIds = [];
//                foreach ($seasonMap as $needle => $seasonName) {
//                    if (Str::contains($seasonText, $needle)) {
//                        $id = Season::where('name', $seasonName)->value('id');
//                        if ($id) $seasonIds[] = $id;
//                    }
//                }
//
//                // Sync seasons (als je relationships hebt)
//                if (method_exists($card, 'seasons')) {
//                    $card->seasons()->sync($seasonIds);
//                } else {
//                    // fallback: direct in pivot
//                    DB::table('card_season')->where('card_id', $card->id)->delete();
//                    foreach (array_unique($seasonIds) as $sid) {
//                        DB::table('card_season')->insert([
//                            'card_id' => $card->id,
//                            'season_id' => $sid,
//                        ]);
//                    }
//                }
//
//                // Location pivot: neem volledige locatie-string als één location record
//                $locationIds = [];
//                if ($locatieText !== '') {
//                    $location = Location::firstOrCreate(['name' => $locatieText]);
//                    $locationIds[] = $location->id;
//                }
//
//                if (method_exists($card, 'locations')) {
//                    $card->locations()->sync($locationIds);
//                } else {
//                    DB::table('card_location')->where('card_id', $card->id)->delete();
//                    foreach (array_unique($locationIds) as $lid) {
//                        DB::table('card_location')->insert([
//                            'card_id' => $card->id,
//                            'location_id' => $lid,
//                        ]);
//                    }
//                }
//
//                // Quiz: 1 vraag per rij + answers JSON
//                $question = trim((string)($data['Vragen'] ?? ''));
//                $correct  = trim((string)($data['Juiste Antwoord'] ?? ''));
//                $wrong1   = trim((string)($data['Onjuiste Antwoord 1'] ?? ''));
//                $wrong2   = trim((string)($data['Onjuiste Antwoord 2'] ?? ''));
//
//                if ($question !== '' && $correct !== '') {
//                    $answers = [
//                        ['id' => 'a1', 'text' => $correct, 'correct' => true],
//                    ];
//                    if ($wrong1 !== '') $answers[] = ['id' => 'a2', 'text' => $wrong1, 'correct' => false];
//                    if ($wrong2 !== '') $answers[] = ['id' => 'a3', 'text' => $wrong2, 'correct' => false];
//
//                    Quiz::updateOrCreate(
//                        ['card_id' => $card->id, 'question_text' => $question],
//                        ['answers' => $answers, 'explanation' => null]
//                    );
//                }
//            }
//        });
//    }
//}
