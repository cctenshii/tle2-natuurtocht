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
                if ($cat === 'bloem') return 'Bloemenrijk';

                $haystack = mb_strtolower(
                    (($row['extra_info'] ?? '') . ' ' . ($row['kenmerken'] ?? '') . ' ' . ($row['feitje'] ?? ''))
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
            $cardsData =[
                [
                    'title' => 'Brandnetel',
                    'category' => 'Plant',
                    'season_text' => 'Lente, Zomer en Herfst',
                    'kenmerken' => 'Heeft groene bladeren met brandhaartjes die prikken',
                    'extra_info' => 'De brandnetel groeit snel op voedselrijke grond. De kleine haartjes op de stengel breken af bij aanraking en geven een prikkelende stof af, waardoor hij brandt.',
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
                    'extra_info' => 'De tulp groeit uit een bol waarin hij voedsel opslaat. In het voorjaar gebruikt de plant deze energie om snel te bloeien.',
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
                    'extra_info' => 'De klaproos is een eenjarige plant die veel zaden maakt. Die zaden kunnen jarenlang in de grond blijven wachten tot ze kunnen groeien.',
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
                    'extra_info' => 'De vlinderstruik is een struik met lange bloemtrossen. Hij groeit snel en kan goed tegen droogte.',
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
                    'extra_info' => 'De hortensia is een struik met grote bloemschermen. De plant houdt van vochtige grond en schaduwrijke plekken.',
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
                    'extra_info' => 'De waterlelie wortelt in de modder op de bodem, terwijl de bladeren en bloemen op het water drijven.',
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
                    'extra_info' => 'De narcis groeit uit een bol en bloeit vroeg in het voorjaar. Na de bloei slaat de plant weer energie op in de bol.',
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
                    'extra_info' => 'De boterbloem groeit vaak in graslanden. De plant bevat stoffen die licht giftig zijn voor dieren.',
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
                    'extra_info' => 'De hyacint is een bolplant met dikke bladeren en veel kleine bloemen dicht bij elkaar.',
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
                    'extra_info' => 'Deze plant heeft een lange stengel met buisvormige bloemen. Hij groeit vaak op plekken waar de grond verstoord is.',
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
                    'extra_info' => 'De appelboom is een loofboom die bloeit in het voorjaar. Na bestuiving groeien de bloemen uit tot appels.',
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
                    'extra_info' => 'Deze boom kan heel oud worden en heeft lange bladeren. De vruchten zitten in een stekelige schil.',
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
                    'extra_info' => 'De kersenboom bloeit eerst en krijgt pas daarna bladeren. De bloemen groeien in groepjes aan de takken.',
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
                    'extra_info' => 'De eik is een sterke boom met diepe wortels. Hij groeit langzaam maar kan honderden jaren oud worden.',
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
                    'extra_info' => 'De beuk heeft gladde bast en brede bladeren. In de herfst verliest hij zijn bladeren, die vaak blijven liggen op de grond.',
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
                    'extra_info' => 'De dennenboom heeft naalden in plaats van bladeren. Deze naalden blijven meerdere jaren aan de boom zitten.',
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
                    'extra_info' => 'De vliegenzwam groeit samen met bomen. De zwam helpt de boom voedingsstoffen op te nemen.',
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
                    'extra_info' => 'Deze paddenstoel heeft geen steel en bestaat vooral uit een bol. Binnenin zitten de sporen.',
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
                    'extra_info' => 'De parasolzwam heeft een lange steel en een grote hoed. Tijdens het groeien gaat de hoed steeds verder open.',
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
                    'extra_info' => 'De tonderzwam groeit op bomen en breekt langzaam hout af.',
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
                    'extra_info' => 'Deze paddenstoel groeit in bossen en vormt een samenwerking met boomwortels.',
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
                    'extra_info' => 'Deze zwam heeft een paarsrode kleur en groeit op dood hout.',
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
                    'extra_info' => 'De reuzenbovist begint klein maar kan uitgroeien tot een enorme ronde paddenstoel.',
                    'feitje' => 'Als hij helemaal wit is van binnen, is het eetbaar en schijnt het erg lekker te zijn',
                    'question' => 'Wat is bijzonder aan de reuzenbovist?',
                    'correct' => 'Hij kan zo groot worden als een voetbal',
                    'wrong1' => 'Hij ruikt naar chocola',
                    'wrong2' => 'Hij is heel klein, kleiner dan een knikker',
                    'seasons' => ['Zomer', 'Herfst'],
                ],
                [
                    'title' => 'Winterjasmijn',
                    'category' => 'Bloem',
                    'season_text' => 'Winter',
                    'kenmerken' => 'Opvallende stervormige gele bloemen op kale takken.',
                    'extra_info' => 'Winterjasmijn is een struik met lange, dunne takken. In de winter maakt hij gele bloemen terwijl de plant geen bladeren heeft. De bloemen groeien direct uit de takken en gebruiken opgeslagen energie.',
                    'feitje' => 'Kan tegen de kou, zelfs als het vriest. Dat maakt hem een soort "superheld-plant"!',
                    'question' => 'Welke bijzondere eigenschap heeft de winterjasmijn?',
                    'correct' => 'Hij bloeit midden in de winter',
                    'wrong1' => 'Hij heeft stekels zoals een roos',
                    'wrong2' => 'Hij ruikt heel sterk naar citroen',
                    'seasons' => ['Winter'],
                ],
                [
                    'title' => 'Paarse dovenetel',
                    'category' => 'Plant',
                    'season_text' => 'Alle seizoenen',
                    'kenmerken' => 'Plant met paarse tot roodachtige bovenste blaadjes, bloemen en een vierkante stengel.',
                    'extra_info' => 'De paarse dovenetel is een lage plant met zachte bladeren. Hij groeit snel en kan al vroeg in het jaar bloeien. De plant verspreidt zich vooral door zaad.',
                    'feitje' => 'Is géén brandnetel! Ondanks zijn naam prikt hij helemaal niet--dus geen paniek.',
                    'question' => 'Waarom heet de paarse dovenetel een "dovenetel"?',
                    'correct' => 'De bladeren lijken op brandnetels, maar prikken niet',
                    'wrong1' => 'De plant is giftig en maakt je oren doof',
                    'wrong2' => 'De bloemen groeien altijd naast echte brandnetels',
                    'seasons' => ['Lente', 'Zomer', 'Herfst', 'Winter'],
                ],
                [
                    'title' => 'Sneeuwklokje',
                    'category' => 'Bloem',
                    'season_text' => 'Winter',
                    'kenmerken' => 'Kleine planten met klokvormige witte bloemen, met subtiele groene vlekjes in het midden.',
                    'extra_info' => 'Het sneeuwklokje groeit uit een kleine bol onder de grond. In de winter zit alle energie opgeslagen in de bol, zodat de plant al heel vroeg kan groeien.',
                    'feitje' => 'De bloem is niet echt wit, maar kleurloos. De kleur komt door de luchtbelletjes tussen de bladcellen die het licht weerkaatsen.',
                    'question' => 'Wanneer zie je vaak de eerste sneeuwklokje?',
                    'correct' => 'Aan het einde van de winter/begin van de lente',
                    'wrong1' => 'In de zomer',
                    'wrong2' => 'Alleen in de herfst',
                    'seasons' => ['Winter'],
                ],
                [
                    'title' => 'Lavendel',
                    'category' => 'Plant',
                    'season_text' => 'Zomer',
                    'kenmerken' => 'Grijze, smalle, aromatische bladeren en paarse (soms witte, roze) bloemen.',
                    'extra_info' => 'Lavendel is een vaste plant met houtige stengels. De smalle bladeren en stevige bouw helpen de plant om te overleven op droge, zonnige plekken.',
                    'feitje' => 'Wordt al eeuwen gebruikt voor parfum, zeep en om kleding lekker te laten ruiken.',
                    'question' => 'Waar staat lavendel vooral om bekend?',
                    'correct' => 'Zijn paarse kleur en lekkere geur',
                    'wrong1' => 'Dat hij heel snel groeit',
                    'wrong2' => 'Dat hij altijd onder water groeit',
                    'seasons' => ['Zomer'],
                ],
                [
                    'title' => 'Herfststijloos',
                    'category' => 'Bloem',
                    'season_text' => 'Lente en Herfst',
                    'kenmerken' => 'Krokusachtige, lila/roze bloemen zonder bladeren.',
                    'extra_info' => 'De herfststijloos heeft een bijzondere groeiwijze. In de herfst verschijnen alleen de bloemen. De bladeren groeien pas later, in het voorjaar, om energie op te slaan.',
                    'feitje' => 'Hij bloeit in de herfst, maar zijn bladeren komen pas in de lente tevoorschijn.',
                    'question' => 'Wat is opvallend aan de herfststijloos?',
                    'correct' => 'Hij bloeit in de herfst maar krijgt zijn bladeren pas in de lente',
                    'wrong1' => 'Hij verandert van kleur als je hem aanraakt',
                    'wrong2' => 'Hij kan groeien in de sneeuw',
                    'seasons' => ['Lente', 'Herfst'],
                ],
                [
                    'title' => 'Munt',
                    'category' => 'Plant',
                    'season_text' => 'Lente, Zomer en Herfst',
                    'kenmerken' => 'Groene bladeren die vaak een beetje kartelig zijn. Kan kleine paars-witte bloemetjes krijgen',
                    'extra_info' => 'Munt is een sterke plant die zich ondergronds verspreidt met wortelstengels. Daardoor kan één plant snel een groot gebied innemen.',
                    'feitje' => 'Er zijn meer dan 20 soorten munt, zoals chocolademunt, aardbeimunt en zelfs ananasmunt. Ja, ze ruiken echt!',
                    'question' => 'Waar wordt munt vaak voor gebruikt?',
                    'correct' => 'In thee en kauwgom voor een frisse smaak',
                    'wrong1' => 'Om meubels schoon te maken',
                    'wrong2' => 'Om dieren op afstand te houden',
                    'seasons' => ['Lente', 'Zomer', 'Herfst'],
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
                    'extra_info' => $row['extra_info'] ?: null,
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

                    $loc = trim((string)($row['extra_info'] ?? ''));
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
