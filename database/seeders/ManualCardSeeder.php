<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class ManualCardSeeder extends Seeder
{
    public function run(): void
    {
        DB::transaction(function () {
            // Get the first user to assign cards to.
            $user = User::first();
            if (!$user) {
                $this->command->error('No users found in the database. Please run UserSeeder first.');
                return;
            }

            // Helper: bepaal "rijk" (voor headings in UI)
            $determineRijk = function (array $row): string {
                $cat = mb_strtolower((string)($row['category'] ?? ''));

                if ($cat === 'boom') return 'Bomenrijk';
                if ($cat === 'schimmel') return 'Schimmelrijk';

                $haystack = mb_strtolower(
                    (($row['locatie'] ?? '') . ' ' . ($row['kenmerken'] ?? '') . ' ' . ($row['feitje'] ?? ''))
                );

                $waterWords = ['water', 'vijver', 'oever', 'plas', 'sloot', 'moeras', 'drijft', 'stilstaand'];
                foreach ($waterWords as $w) {
                    if (str_contains($haystack, $w)) {
                        return 'Water- en oeverplanten';
                    }
                }

                return 'Struikenrijk';
            };

            // Base seasons
            $baseSeasons = ['Lente', 'Zomer', 'Herfst', 'Winter'];
            foreach ($baseSeasons as $seasonName) {
                DB::table('seasons')->updateOrInsert(['name' => $seasonName], ['name' => $seasonName]);
            }

            // Data
            $cardsData = [
                [
                    'title' => 'Brandnetel',
                    'category' => 'Plant',
                    'season_text' => 'Lente, Zomer en Herfst',
                    'kenmerken' => 'Heeft groene bladeren met brandhaartjes die prikken',
                    'locatie' => 'Vochtige, voedselrijke grond, vaak langs paden en bosranden',
                    'feitje' => 'Ondanks dat hij prikt, zit de brandnetel vol vitamines en wordt hij gebruikt voor thee!',
                    'question' => 'Welke eigenschap heeft de brandnetel?',
                    'correct' => 'Hij heeft prikhaartjes',
                    'wrong1' => 'Hij ruikt heel zoet',
                    'wrong2' => 'Hij groeit alleen in de winter',
                    'seasons' => ['Lente', 'Zomer', 'Herfst'],
                ],
                [
                    'title' => 'Tulp',
                    'category' => 'Bloem',
                    'season_text' => 'Lente',
                    'kenmerken' => 'Grote bloem in allerlei kleuren',
                    'locatie' => 'Tuinen en parken',
                    'feitje' => 'Tulpen zijn ooit zó populair geweest dat ze even meer waard waren dan goud',
                    'question' => 'Wanneer bloeit een tulp meestal',
                    'correct' => 'In de lente',
                    'wrong1' => 'In de zomer',
                    'wrong2' => 'In de winter',
                    'seasons' => ['Lente'],
                ],
                [
                    'title' => 'Klaproos',
                    'category' => 'Bloem',
                    'season_text' => 'Lente en Zomer',
                    'kenmerken' => 'Rode, dunne blaadjes en een donker hartje',
                    'locatie' => 'Bermen, akkers, open velden',
                    'feitje' => 'De klaproos heeft zo\'n dunne blaadjes dat ze lijken op papier',
                    'question' => 'Wat is typisch aan de klaproos?',
                    'correct' => 'Hij heeft dunne rode blaadjes',
                    'wrong1' => 'Hij groeit alleen op water',
                    'wrong2' => 'Hij ruikt heel sterk',
                    'seasons' => ['Lente', 'Zomer'],
                ],
                [
                    'title' => 'Vlinderstruik',
                    'category' => 'Bloem',
                    'season_text' => 'Zomer en Herfst',
                    'kenmerken' => 'Grote paarse, roze of witte bloemtrossen',
                    'locatie' => 'Tuinen, randen van struwelen',
                    'feitje' => 'Deze struik werkt als een "vlinderhotel" -- vlinders kunnen er niet vanaf blijven',
                    'question' => 'Waarom heet het een vlinderstruik?',
                    'correct' => 'Vlinders komen graag op de bloemen af',
                    'wrong1' => 'De bladeren lijken op vlindervleugels',
                    'wrong2' => 'De struik beweegt als een vlinder',
                    'seasons' => ['Zomer', 'Herfst'],
                ],
                [
                    'title' => 'Hortensia',
                    'category' => 'Bloem',
                    'season_text' => 'Zomer en Herfst',
                    'kenmerken' => 'Grote bolvormige bloemen in blauw, roze of wit',
                    'locatie' => 'Tuinen en parken',
                    'feitje' => 'De kleur van hortensia\'s verandert door de bodem: zure grond = blauw, kalkrijke grond = roze',
                    'question' => 'Waardoor kan de kleur van een hortensia veranderen?',
                    'correct' => 'Door de zuurgraad van de bodem',
                    'wrong1' => 'Door het aantal zonuren',
                    'wrong2' => 'Door de kleur van de bladen',
                    'seasons' => ['Zomer', 'Herfst'],
                ],
                [
                    'title' => 'Waterlelie',
                    'category' => 'Bloem',
                    'season_text' => 'Zomer',
                    'kenmerken' => 'Drijft op het water met ronde bladeren',
                    'locatie' => 'Vijvers en stilstaand water',
                    'feitje' => 'Waterlelies sluiten hun bloemen \'s avonds en gaan weer open als de zon opkomt',
                    'question' => 'Waar groeit een waterlelie?',
                    'correct' => 'In stilstaand water',
                    'wrong1' => 'In het bos',
                    'wrong2' => 'Op droge zandgrond',
                    'seasons' => ['Zomer'],
                ],
                [
                    'title' => 'Narcis',
                    'category' => 'Bloem',
                    'season_text' => 'Lente',
                    'kenmerken' => 'Gele of witte bloem met een "trompet" in het midden',
                    'locatie' => 'Tuinen, parken en bermen',
                    'feitje' => 'Narcissen kunnen zichzelf verdedigen: ze maken een stof die voorkomt dat dieren ze opeten',
                    'question' => 'Hoe herken je een narcis?',
                    'correct' => 'Aan een grote trompetvorm in het midden',
                    'wrong1' => 'Aan de paarse blaadjes',
                    'wrong2' => 'Aan de scherpe stekels aan de stengel',
                    'seasons' => ['Lente'],
                ],
                [
                    'title' => 'Boterbloem',
                    'category' => 'Bloem',
                    'season_text' => 'Lente en Zomer',
                    'kenmerken' => 'Kleine, glanzende, gele bloemetjes',
                    'locatie' => 'Weilanden, vochtige graslanden',
                    'feitje' => 'De glans op een boterbloem werkt als een mini-spiegeltje -- daarom reflecteert hij zo veel licht',
                    'question' => 'Hoe zien de blaadjes van een boterbloem eruit?',
                    'correct' => 'Glanzend en geel',
                    'wrong1' => 'Donkerpaars en groot',
                    'wrong2' => 'Wit en pluizig',
                    'seasons' => ['Lente', 'Zomer'],
                ],
                [
                    'title' => 'Hyacint',
                    'category' => 'Bloem',
                    'season_text' => 'Lente',
                    'kenmerken' => 'Geurige bloemen die dicht op elkaar zitten op 1 stengel',
                    'locatie' => 'Tuinen en parken',
                    'feitje' => 'Eén hyacint kan een hele klas laten ruiken alsof er parfum is omgewaaid',
                    'question' => 'Wat is opvallend aan een hyacint?',
                    'correct' => 'Hij ruikt heel sterk',
                    'wrong1' => 'Hij heeft maar één bloem',
                    'wrong2' => 'Hij groeit alleen onder water',
                    'seasons' => ['Lente'],
                ],
                [
                    'title' => 'Vingerhoedskruid',
                    'category' => 'Bloem',
                    'season_text' => 'Lente en Zomer',
                    'kenmerken' => 'Lange stengel met buisvormige paarse of roze bloemen | LET OP GIFTIG',
                    'locatie' => 'Bosranden en heide',
                    'feitje' => 'De bloemen lijken op kleine belletjes waar bijen precies in kunnen kruipen',
                    'question' => 'Welke vorm hebben de bloemen van vingerhoedskruid?',
                    'correct' => 'Buisjes die op de vingerhoedjes lijken',
                    'wrong1' => 'Platte schoteltjes',
                    'wrong2' => 'Sterrenvormige bloemetjes',
                    'seasons' => ['Lente', 'Zomer'],
                ],
                [
                    'title' => 'Appelboom',
                    'category' => 'Boom',
                    'season_text' => 'Alle seizoenen (Lente: bloeien, Zomer: groeien vruchten, Herfst: oogsten, Winter: rust)',
                    'kenmerken' => 'Roze-witte bloemetjes in de lente, Appels groeien in de zomer',
                    'locatie' => '',
                    'feitje' => 'Appels drijven op water omdat ze voor 25% uit lucht bestaan',
                    'question' => 'Wat gebeurt er in de herfst met een appelboom?',
                    'correct' => 'De appels zijn klaar om geplukt te worden',
                    'wrong1' => 'De boom krijgt voor het eerst bloemen',
                    'wrong2' => 'De bladeren worden roze',
                    'seasons' => ['Lente', 'Zomer', 'Herfst', 'Winter'],
                ],
                [
                    'title' => 'Tamme kastanjeboom',
                    'category' => 'Boom',
                    'season_text' => 'Alle seizoenen (Zomer: bloeien, Herfst: vruchten vallen)',
                    'kenmerken' => 'Lange bladeren met tandjes aan de rand',
                    'locatie' => '',
                    'feitje' => 'De stekelige bolsters beschermen de kastanje alsof het een mini-ridderspantser is',
                    'question' => 'Waar zitten tamme kastanjes in?',
                    'correct' => 'In een stekelige bolster',
                    'wrong1' => 'In een gladde groene bol',
                    'wrong2' => 'Los aan de tak zonder bescherming',
                    'seasons' => ['Zomer', 'Herfst'],
                ],
                [
                    'title' => 'Kersenbloesem',
                    'category' => 'Boom',
                    'season_text' => 'Alle seizoenen (Lente: bloeien de bloemen)',
                    'kenmerken' => 'Heel veel roze of witte bloemetjes in de lente',
                    'locatie' => 'Parken en lanen',
                    'feitje' => 'In Japan vieren mensen een feest onder kersenbloesem: Hanami, om de bloesems te bewonderen',
                    'question' => 'Waar staat de kersenbloesem vooral om bekend?',
                    'correct' => 'Om de vele roze of witte bloemetjes',
                    'wrong1' => 'Om z\'n sterke geur',
                    'wrong2' => 'Om de grote vruchten',
                    'seasons' => ['Lente'],
                ],
                [
                    'title' => 'Eik',
                    'category' => 'Boom',
                    'season_text' => 'Alle seizoenen (Lente: bloeien, Zomer: groene bladeren, Herfst: herfstkleur en eikels vallen)',
                    'kenmerken' => 'Herkenbaar aan de gelobde bladeren en eikels',
                    'locatie' => '',
                    'feitje' => 'Eikels zijn het favoriete eten van veel dieren, zoals eekhoorns en gaaien',
                    'question' => 'Welke vrucht hoort bij de eik?',
                    'correct' => 'Eikels',
                    'wrong1' => 'Kastanjes',
                    'wrong2' => 'Pruimen',
                    'seasons' => ['Lente', 'Zomer', 'Herfst'],
                ],
                [
                    'title' => 'Beuk',
                    'category' => 'Boom',
                    'season_text' => 'Alle seizoenen (Lente: rode bladeren, Zomer: groen bladerdak, Herfst: bruine bladeren en vallen af of blijven, Winter: kaal)',
                    'kenmerken' => 'Gladde, grijze stam en stevige bladeren',
                    'locatie' => '',
                    'feitje' => 'De bast van een beuk is zo glad dat mensen vroeger hun initialen erin kerfden -- dat blijft jaren zichtbaar',
                    'question' => 'Wat is typisch aan de stam van een breuk?',
                    'correct' => 'Hij is glad en grijzig',
                    'wrong1' => 'Hij is heel dik behaard',
                    'wrong2' => 'Hij is knalrood van kleur',
                    'seasons' => ['Lente', 'Zomer', 'Herfst', 'Winter'],
                ],
                [
                    'title' => 'Dennenboom',
                    'category' => 'Boom',
                    'season_text' => 'Alle seizoenen (Hele jaar groen, Herfst: produceren dennenappels, Winter: zaden verspreiden)',
                    'kenmerken' => 'Heeft naalden in plaats van bladeren en blijft het hele jaar groen',
                    'locatie' => '',
                    'feitje' => 'Een dennenappel kan jaren dicht blijven zitten tot het warm genoeg is om zaden los te laten',
                    'question' => 'Wat valt er van een dennenboom om zaden te verspreiden?',
                    'correct' => 'Dennenappels',
                    'wrong1' => 'Eikels',
                    'wrong2' => 'Appels',
                    'seasons' => ['Herfst', 'Winter'],
                ],
                [
                    'title' => 'Vliegenzwam',
                    'category' => 'Schimmel',
                    'season_text' => 'Herfst',
                    'kenmerken' => 'Rode hoed met witte stippen -- bekend uit sprookjes',
                    'locatie' => 'Vooral bij berken en dennen',
                    'feitje' => 'Deze paddenstoel wordt zo vaak in sprookjes getekend dat hij de "sprookjespaddenstoel" wordt genoemd',
                    'question' => 'Hoe ziet de bekende vliegenzwam eruit?',
                    'correct' => 'Rode hoed met witte stippen',
                    'wrong1' => 'Bruine hoed met gele stippen',
                    'wrong2' => 'Rode hoed met witte strepen',
                    'seasons' => ['Herfst'],
                ],
                [
                    'title' => 'Aardappelbovist',
                    'category' => 'Schimmel',
                    'season_text' => 'Zomer tot begin Winter',
                    'kenmerken' => 'Ronde bruine bol',
                    'locatie' => '',
                    'feitje' => 'Als hij openbarst, stuift hij als een kleine rookbommetje',
                    'question' => 'Wat komt eruit als een aardappelbovist openbarst?',
                    'correct' => 'Een wolkje sporen',
                    'wrong1' => 'Water',
                    'wrong2' => 'Melksap',
                    'seasons' => ['Zomer', 'Winter'],
                ],
                [
                    'title' => 'Parasolzwam',
                    'category' => 'Schimmel',
                    'season_text' => 'Late Zomer en Herfst',
                    'kenmerken' => 'Ziet eruit als een kleine parasol op een lange steel',
                    'locatie' => '',
                    'feitje' => 'De hoed kan zo groot worden dat hij een echte mini-parasol lijkt',
                    'question' => 'Waarom heet de parasolzwam zo?',
                    'correct' => 'De hoed lijkt op een kleine parasol',
                    'wrong1' => 'Hij groeit altijd naast parasolplanten',
                    'wrong2' => 'Hij beschermt dieren tegen regen',
                    'seasons' => ['Zomer', 'Herfst'],
                ],
                [
                    'title' => 'Tonderzwam',
                    'category' => 'Schimmel',
                    'season_text' => 'Alle seizoenen',
                    'kenmerken' => 'Hard en lijkt op een hoef aan de stam van een boom',
                    'locatie' => '',
                    'feitje' => 'Oermensen gebruiken deze harde zwam als vuurstarter om vonken te vangen',
                    'question' => 'Waar groeit een tonderzwam vooral?',
                    'correct' => 'Op boomstammen',
                    'wrong1' => 'In weilanden',
                    'wrong2' => 'Onder water',
                    'seasons' => ['Lente', 'Zomer', 'Herfst', 'Winter'],
                ],
                [
                    'title' => 'Eekhoorntjesbrood',
                    'category' => 'Schimmel',
                    'season_text' => 'Alle seizoenen',
                    'kenmerken' => 'Dikke paddenstoel met bruine hoed',
                    'locatie' => '',
                    'feitje' => 'Deze paddenstoel is zo geliefd dat sommige dieren hem verstoppen voor later -- net als mensen met koekjes',
                    'question' => 'Hoe ziet eekhoorntjesbrood eruit?',
                    'correct' => 'Een stevige bruine hoed op een dikke steel',
                    'wrong1' => 'Dun en doorschijnend',
                    'wrong2' => 'Felblauw en heel klein',
                    'seasons' => ['Lente', 'Zomer', 'Herfst', 'Winter'],
                ],
                [
                    'title' => 'Rodekoolzwam',
                    'category' => 'Schimmel',
                    'season_text' => 'Late Zomer en Herfst',
                    'kenmerken' => 'Paarse paddenstoel die een beetje glimt',
                    'locatie' => '',
                    'feitje' => 'Hij heet zo omdat zijn paarsee kleur lijkt op die van rode kool',
                    'question' => 'Waar lijkt de kleur van de rodekoolzwam op?',
                    'correct' => 'Rode kool',
                    'wrong1' => 'Sinaasappel',
                    'wrong2' => 'Rode biet',
                    'seasons' => ['Zomer', 'Herfst'],
                ],
                [
                    'title' => 'Reuzenbovist',
                    'category' => 'Schimmel',
                    'season_text' => 'Zomer en Herfst',
                    'kenmerken' => 'Een enorme witte bol, soms zo groot als een voetbal',
                    'locatie' => '',
                    'feitje' => 'Als hij helemaal wit is van binnen, is het eetbaar en schijnt het erg lekker te zijn',
                    'question' => 'Wat is bijzonder aan de reuzenbovist?',
                    'correct' => 'Hij kan zo groot worden als een voetbal',
                    'wrong1' => 'Hij ruikt naar chocola',
                    'wrong2' => 'Hij is heel klein, kleiner dan een knikker',
                    'seasons' => ['Zomer', 'Herfst'],
                ],
            ];

            foreach ($cardsData as $row) {
                // Category
                DB::table('categories')->updateOrInsert(
                    ['name' => $row['category']],
                    ['name' => $row['category']]
                );
                $categoryId = DB::table('categories')->where('name', $row['category'])->value('id');

                // Properties
                $properties = [
                    'rijk' => $determineRijk($row),
                    'seizoen' => $row['season_text'] ?: null,
                    'feitje' => $row['feitje'] ?: null,
                    'kenmerken' => $row['kenmerken'] ?: null,
                    'locatie_text' => $row['locatie'] ?: null,
                ];

                // Placeholder image uses title
                $imageUrl = 'https://placehold.co/400x300/DDD/777?text=' . rawurlencode($row['title']);

                // Card (title instead of name)
                DB::table('cards')->updateOrInsert(
                    ['title' => $row['title']],
                    [
                        'category_id' => $categoryId,
                        'description' => $row['kenmerken'] ?: ($row['feitje'] ?: null),
                        'properties' => json_encode($properties, JSON_UNESCAPED_UNICODE),
                        'image_url' => $imageUrl,
                    ]
                );

                $cardId = DB::table('cards')->where('title', $row['title'])->value('id');

                // Seasons pivot (card_season)
                if (Schema::hasTable('card_season')) {
                    DB::table('card_season')->where('card_id', $cardId)->delete();

                    foreach (($row['seasons'] ?? []) as $seasonName) {
                        $seasonId = DB::table('seasons')->where('name', $seasonName)->value('id');
                        if ($seasonId) {
                            DB::table('card_season')->insertOrIgnore([
                                'card_id' => $cardId,
                                'season_id' => $seasonId,
                            ]);
                        }
                    }
                }

                // Locations pivot (card_location)
                if (Schema::hasTable('card_location')) {
                    DB::table('card_location')->where('card_id', $cardId)->delete();

                    $loc = trim((string)($row['locatie'] ?? ''));
                    if ($loc !== '' && Schema::hasTable('locations')) {
                        DB::table('locations')->updateOrInsert(['name' => $loc], ['name' => $loc]);
                        $locationId = DB::table('locations')->where('name', $loc)->value('id');

                        if ($locationId) {
                            DB::table('card_location')->insertOrIgnore([
                                'card_id' => $cardId,
                                'location_id' => $locationId,
                            ]);
                        }
                    }
                }

                // Quiz (table name: quiz)
                if (Schema::hasTable('quiz')) {
                    $q = trim((string)($row['question'] ?? ''));
                    $correct = trim((string)($row['correct'] ?? ''));

                    if ($q !== '' && $correct !== '') {
                        $answers = [
                            ['id' => '1', 'text' => $correct, 'correct' => true],
                        ];

                        $w1 = trim((string)($row['wrong1'] ?? ''));
                        $w2 = trim((string)($row['wrong2'] ?? ''));

                        if ($w1 !== '') $answers[] = ['id' => '2', 'text' => $w1, 'correct' => false];
                        if ($w2 !== '') $answers[] = ['id' => '3', 'text' => $w2, 'correct' => false];

                        DB::table('quiz')->updateOrInsert(
                            ['card_id' => $cardId, 'question_text' => $q],
                            [
                                'answers' => json_encode($answers, JSON_UNESCAPED_UNICODE),
                                'explanation' => null,
                            ]
                        );
                    }
                }
            }
        });
    }
}
